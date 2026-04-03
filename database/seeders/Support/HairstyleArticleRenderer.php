<?php

namespace Database\Seeders\Support;

/**
 * Render bài viết kiểu tóc dạng blog SEO từ dữ liệu cố định.
 *
 * @phpstan-type HairstyleArticleDefinition array{
 *     title: string,
 *     slug: string,
 *     description: string,
 *     seo_title: string,
 *     seo_description: string,
 *     published_at: string,
 *     featured_image: string,
 *     price_from: int,
 *     companion_services: list<string>,
 *     sections: array<string, list<string>>,
 *     pros: list<string>,
 *     cons: list<string>,
 *     faq: list<array{question: string, answer: string}>,
 *     conclusion: list<string>,
 * }
 */
final class HairstyleArticleRenderer
{
    /** @var array<string, string> */
    private const SECTION_TITLES = [
        'introduction' => 'Giới thiệu kiểu tóc',
        'face_shapes' => 'Kiểu tóc phù hợp với khuôn mặt nào',
        'age_groups' => 'Phù hợp với độ tuổi nào',
        'occupations' => 'Phù hợp với nghề nghiệp nào',
        'daily_styling' => 'Cách tạo kiểu hằng ngày',
        'aftercare' => 'Cách chăm sóc sau khi cắt',
        'maintenance_interval' => 'Bao lâu nên cắt lại',
        'color_perm' => 'Có nên nhuộm/uốn cùng kiểu tóc này không',
    ];

    /**
     * @param  HairstyleArticleDefinition  $definition
     */
    public static function render(array $definition): string
    {
        $imageUrl = '/storage/'.str_replace('\\', '/', $definition['featured_image']);
        $published = htmlspecialchars($definition['published_at'], ENT_QUOTES, 'UTF-8');
        $slug = htmlspecialchars($definition['slug'], ENT_QUOTES, 'UTF-8');
        $seoTitle = htmlspecialchars($definition['seo_title'], ENT_QUOTES, 'UTF-8');
        $seoDescription = htmlspecialchars($definition['seo_description'], ENT_QUOTES, 'UTF-8');
        $title = htmlspecialchars($definition['title'], ENT_QUOTES, 'UTF-8');

        $html = <<<HTML
<div class="hairstyle-article" data-slug="{$slug}" data-seo-title="{$seoTitle}" data-seo-description="{$seoDescription}" data-published-at="{$published}">
<p class="article-meta"><time datetime="{$published}">Đăng ngày {$published}</time></p>
<h1>{$title}</h1>
<figure class="article-featured-image"><img src="{$imageUrl}" alt="{$title}" loading="lazy" /></figure>
HTML;

        foreach (self::SECTION_TITLES as $key => $heading) {
            $html .= self::renderSection($heading, $definition['sections'][$key] ?? []);
        }

        $html .= self::renderListSection('Ưu điểm', $definition['pros']);
        $html .= self::renderListSection('Nhược điểm', $definition['cons']);
        $html .= self::renderPriceSection($definition['price_from']);
        $html .= self::renderListSection('Gợi ý dịch vụ đi kèm', $definition['companion_services']);
        $html .= self::renderFaq($definition['faq']);
        $html .= self::renderSection('Kết luận', $definition['conclusion']);

        return $html.'</div>';
    }

    /**
     * @param  list<string>  $paragraphs
     */
    private static function renderSection(string $heading, array $paragraphs): string
    {
        if ($paragraphs === []) {
            return '';
        }

        $html = '<h2>'.htmlspecialchars($heading, ENT_QUOTES, 'UTF-8').'</h2>';

        foreach ($paragraphs as $paragraph) {
            $html .= '<p>'.htmlspecialchars($paragraph, ENT_QUOTES, 'UTF-8').'</p>';
        }

        return $html;
    }

    /**
     * @param  list<string>  $items
     */
    private static function renderListSection(string $heading, array $items): string
    {
        if ($items === []) {
            return '';
        }

        $html = '<h2>'.htmlspecialchars($heading, ENT_QUOTES, 'UTF-8').'</h2><ul>';

        foreach ($items as $item) {
            $html .= '<li>'.htmlspecialchars($item, ENT_QUOTES, 'UTF-8').'</li>';
        }

        return $html.'</ul>';
    }

    private static function renderPriceSection(int $priceFrom): string
    {
        $formatted = number_format($priceFrom, 0, ',', '.');

        return '<h2>Giá tham khảo</h2><p>Giá cắt kiểu tóc này tại salon thường từ <strong>'.$formatted.'đ</strong> (chưa gồm gội, tạo kiểu hoặc nhuộm). Mức giá cụ thể phụ thuộc độ dài tóc, stylist và gói dịch vụ bạn chọn khi đặt lịch.</p>';
    }

    /**
     * @param  list<array{question: string, answer: string}>  $faq
     */
    private static function renderFaq(array $faq): string
    {
        if ($faq === []) {
            return '';
        }

        $html = '<h2>Câu hỏi thường gặp (FAQ)</h2>';

        foreach ($faq as $item) {
            $html .= '<h3>'.htmlspecialchars($item['question'], ENT_QUOTES, 'UTF-8').'</h3>';
            $html .= '<p>'.htmlspecialchars($item['answer'], ENT_QUOTES, 'UTF-8').'</p>';
        }

        return $html;
    }
}
