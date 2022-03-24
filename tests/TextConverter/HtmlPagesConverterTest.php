<?php

declare(strict_types=1);

namespace Tests\TextConverter;

use PHPUnit\Framework\TestCase;
use RacingCar\TextConverter\HtmlPagesConverter;

class HtmlPagesConverterTest extends TestCase
{
    protected function getStubPath(): string
    {
        return __DIR__ . '/Stubs/pages_stub.txt';
    }

    protected function tearDown(): void
    {
        file_put_contents($this->getStubPath(), '');

        parent::tearDown();
    }

    /** @test */
    public function it_opens_a_correct_page_file(): void
    {
        $converter = new HtmlPagesConverter($this->getStubPath());

        $this->assertSame($this->getStubPath(), $converter->getFileName());
    }

    /** @test */
    public function it_adds_break_element_to_each_line_and_splits_into_separate_pages(): void
    {
        $initial = <<<EOT
            1 bottle of beer on the wall
            1 bottle of beer
            PAGE_BREAK
            Take one down and pass it around
            No more bottles of beer on the wall
            PAGE_BREAK
            2 bottles of beer on the wall
            2 bottles of beer
            PAGE_BREAK
            Take one down and pass it around
            No more bottles of beer on the wall
            PAGE_BREAK
            
            EOT;

        file_put_contents($this->getStubPath(), $initial);

        $converter = new HtmlPagesConverter($this->getStubPath());

        $this->assertSame(
            '1 bottle of beer on the wall<br />1 bottle of beer<br />',
            $converter->getHtmlPage(0)
        );

        $this->assertSame(
            '2 bottles of beer on the wall<br />2 bottles of beer<br />',
            $converter->getHtmlPage(2)
        );
    }

    /** @test */
    public function it_adds_break_element_to_each_line_and_splits_into_separate_pages_while_handling_html_tags(): void
    {
        $initial = <<<EOT
            <span>1 bottle of beer on the wall</span>
            1 bottle of beer
            PAGE_BREAK
            Take one down and pass it around
            No more bottles of beer on the wall
            PAGE_BREAK
            <span>2 bottles of beer on the wall</span>
            2 bottles of beer
            PAGE_BREAK
            Take one down and pass it around
            No more bottles of beer on the wall
            PAGE_BREAK
            
            EOT;

        file_put_contents($this->getStubPath(), $initial);

        $converter = new HtmlPagesConverter($this->getStubPath());

        $this->assertSame(
            '&lt;span&gt;1 bottle of beer on the wall&lt;/span&gt;<br />1 bottle of beer<br />',
            $converter->getHtmlPage(0)
        );

        $this->assertSame(
            '&lt;span&gt;2 bottles of beer on the wall&lt;/span&gt;<br />2 bottles of beer<br />',
            $converter->getHtmlPage(2)
        );
    }
}
