<?php

namespace spec\Sylius\Bundle\CoreBundle\Fixture\Factory;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Bundle\CoreBundle\Fixture\Factory\ExampleFactoryInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Locale\Model\Locale;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Taxonomy\Generator\TaxonSlugGeneratorInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;

class TaxonExampleFactorySpec extends ObjectBehavior
{
    function let(
        FactoryInterface $taxonFactory,
        TaxonRepositoryInterface $taxonRepository,
        RepositoryInterface $localeRepository,
        TaxonSlugGeneratorInterface $taxonSlugGenerator
    ) {
        $this->beConstructedWith($taxonFactory, $taxonRepository, $localeRepository, $taxonSlugGenerator);
    }

    function it_is_an_example_factory(): void
    {
        $this->shouldHaveType(ExampleFactoryInterface::class);
    }

    function it_creates_translations_for_each_defined_locales(
        FactoryInterface $taxonFactory,
        RepositoryInterface $localeRepository,
        Locale $locale,
        TaxonInterface $taxon
    ) {
        $taxonFactory->createNew()->willReturn($taxon);
        $localeRepository->findAll()->willReturn([$locale]);
        $locale->getCode()->willReturn('en_US');

        $taxon->setCurrentLocale('en_US');
        $taxon->setFallbackLocale('en_US');
        $taxon->setCode('Category')->shouldBeCalled();
        $taxon->setName('Category')->shouldBeCalled();
        $taxon->setSlug('category')->shouldBeCalled();
        $taxon->setDescription(Argument::type('string'))->shouldBeCalled();

        $this->create([
            'name' => 'Category',
            'slug' => 'category'
        ]);
    }

    function it_creates_translations_for_each_custom_translations(
        FactoryInterface $taxonFactory,
        RepositoryInterface $localeRepository,
        Locale $locale,
        TaxonInterface $taxon
    ) {
        $taxonFactory->createNew()->willReturn($taxon);
        $localeRepository->findAll()->willReturn([$locale]);
        $locale->getCode()->willReturn('en_US');

        $taxon->setCurrentLocale('en_US');
        $taxon->setFallbackLocale('en_US');
        $taxon->setCode('Category')->shouldBeCalled();
        $taxon->setName('Category')->shouldBeCalled();
        $taxon->setSlug('category')->shouldBeCalled();
        $taxon->setDescription(Argument::type('string'))->shouldBeCalled();

        $taxon->setCurrentLocale('fr_FR');
        $taxon->setFallbackLocale('fr_FR');
        $taxon->setName('Catégorie')->shouldBeCalled();
        $taxon->setSlug('categorie')->shouldBeCalled();
        $taxon->setDescription(Argument::type('string'))->shouldBeCalled();

        $this->create([
            'name' => 'Category',
            'slug' => 'category',
            'translations' => [
                'fr_FR' => [
                    'name' => 'Catégorie',
                    'slug' => 'categorie',
                ],
            ],
        ]);
    }

    function it_replaces_existing_translations_for_each_custom_translations(
        FactoryInterface $taxonFactory,
        RepositoryInterface $localeRepository,
        Locale $locale,
        TaxonInterface $taxon
    ) {
        $taxonFactory->createNew()->willReturn($taxon);
        $localeRepository->findAll()->willReturn([$locale]);
        $locale->getCode()->willReturn('en_US');

        $taxon->setCurrentLocale('en_US');
        $taxon->setFallbackLocale('en_US');
        $taxon->setCode('Category')->shouldBeCalled();
        $taxon->setName('Category')->shouldBeCalled();
        $taxon->setSlug('category')->shouldBeCalled();
        $taxon->setDescription(Argument::type('string'))->shouldBeCalled();

        $taxon->setCurrentLocale('en_US');
        $taxon->setFallbackLocale('en_US');
        $taxon->setName('Categories')->shouldBeCalled();
        $taxon->setSlug('categories')->shouldBeCalled();
        $taxon->setDescription(Argument::type('string'))->shouldBeCalled();

        $this->create([
            'name' => 'Category',
            'slug' => 'category',
            'translations' => [
                'en_US' => [
                    'name' => 'Categories',
                    'slug' => 'categories',
                ],
            ],
        ]);
    }
}
