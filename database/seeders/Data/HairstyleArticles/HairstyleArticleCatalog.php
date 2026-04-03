<?php

namespace Database\Seeders\Data\HairstyleArticles;

/**
 * Catalog tất cả bài viết kiểu tóc — mỗi class trả về một định nghĩa cố định.
 */
final class HairstyleArticleCatalog
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public static function definitions(): array
    {
        $classes = [
            MaleLayerArticle::class,
            MaleFadeArticle::class,
            MaleMohicanArticle::class,
            MaleUndercutArticle::class,
            MaleSidePartArticle::class,
            MaleBuzzCutArticle::class,
            MaleTexturedCropArticle::class,
            MaleQuiffArticle::class,
            MalePompadourArticle::class,
            MaleTwoBlockArticle::class,
            MaleFrenchCropArticle::class,
            FemaleLayerArticle::class,
            FemaleWolfCutArticle::class,
            FemaleBobArticle::class,
            FemalePixieArticle::class,
            FemaleHimeArticle::class,
            FemaleLongLayerArticle::class,
            FemaleCurtainBangsArticle::class,
            FemaleButterflyArticle::class,
            FemaleShagArticle::class,
            FemaleWavyArticle::class,
            FemaleMediumLayerArticle::class,
            FemaleShortBobArticle::class,
            FemaleLongStraightArticle::class,
        ];

        $definitions = [];

        foreach ($classes as $class) {
            $definition = $class::definition();
            $definitions[$definition['gender'].':'.$definition['style_name']] = $definition;
        }

        return $definitions;
    }
}
