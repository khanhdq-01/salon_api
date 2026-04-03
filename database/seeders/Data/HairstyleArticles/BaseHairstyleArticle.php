<?php

namespace Database\Seeders\Data\HairstyleArticles;

/**
 * Base helper cho các bài viết kiểu tóc.
 */
abstract class BaseHairstyleArticle
{
    /**
     * @param  list<string>  $paragraphs
     * @return list<string>
     */
    protected static function paragraphs(string ...$paragraphs): array
    {
        return $paragraphs;
    }

    /**
     * @return list<array{question: string, answer: string}>
     */
    protected static function faq(string $question, string $answer): array
    {
        return [['question' => $question, 'answer' => $answer]];
    }

    /**
     * @param  list<array{question: string, answer: string}>  $items
     * @return list<array{question: string, answer: string}>
     */
    protected static function mergeFaq(array ...$items): array
    {
        return array_merge(...$items);
    }
}
