<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\CatPicture;
use App\Entity\Kittie;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class KittieFixture extends Fixture implements DependentFixtureInterface
{
    public static array $catBreeds = array(
        "Persian",
        "Siamese",
        "Maine Coon",
        "Ragdoll",
        "Bengal",
        "British Shorthair",
        "Abyssinian",
        "Sphynx",
        "Scottish Fold",
        "Burmese",
        "Norwegian Forest Cat",
        "Devon Rex",
        "Oriental Shorthair",
        "American Shorthair",
        "Russian Blue",
        "Turkish Angora",
        "Cornish Rex",
        "Birman",
        "Manx",
        "Himalayan"
    );


    private readonly Generator $generator;
    private readonly string $imagesDir;
    private readonly MimeTypes $mimeTypes;

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        ParameterBagInterface $parameterBag,
    ) {
        $this->generator = Factory::create();
        $this->imagesDir = "{$parameterBag->get('kernel.project_dir')}/var/files";
        $this->mimeTypes = new MimeTypes();
    }

    public function load(ObjectManager $manager): void
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->imagesDir);
        $filesystem->mkdir($this->imagesDir);

        /** @var User[] $users */
        $users = $manager->getRepository(User::class)->findAll();

        $images = $this->getImages();
        for ($i = 1; $i <= 50; ++$i) {
            $manager->persist((new Kittie())
                ->setName($this->generator->name())
                ->setBreed(self::$catBreeds[\array_rand(self::$catBreeds)])
                ->setCatPicture(\array_pop($images))
                ->setCreatedBy($users[\array_rand($users)]->getUserIdentifier())
                ->setCreatedAt($this->generator->dateTime())
            );
            if (0 === $i % 10) {
                $manager->flush();
                $manager->clear();
                $images = $this->getImages();
            }
        }
        $manager->flush();
        $manager->clear();
    }

    /** @return CatPicture[] */
    private function getImages(): array
    {
        try {
            $images = [];
            $uris = \array_map(
                static fn (array $i) => $i['url'],
                $this->httpClient->request('GET', 'https://api.thecatapi.com/v1/images/search', ['query' => ['limit' => 10]])->toArray()
            );
            $responses = [];
            foreach ($uris as $uri) {
                $responses[] = $this->httpClient->request('GET', $uri);
            }

            foreach ($this->httpClient->stream($responses) as $response => $chunk) {
                if ($chunk->isLast()) {
                    $url = $response->getInfo()['url'];
                    $ext = \substr($url, \strrpos($url, '.'));
                    $name = $this->generator->uuid();
                    $path = "{$this->imagesDir}/$name$ext";
                    $content = $response->getContent();
                    \file_put_contents($path, $content);
                    $images[] = (new CatPicture())
                        ->setName($name)
                        ->setPath($path)
                        ->setSize((string) \strlen($content))
                        ->setMimeType($this->mimeTypes->guessMimeType($path))
                    ;
                }
            }
            return $images;
        } catch (\Throwable $t) {
            throw new \RuntimeException(\sprintf('Error occurred while downloading images: "%s"', $t));
        }
    }

    /** @return class-string<Fixture>[] */
    public function getDependencies(): array
    {
        return [UserFixture::class];
    }
}
