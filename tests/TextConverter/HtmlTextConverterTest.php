<?php

declare(strict_types=1);

namespace Tests\TextConverter;

use PHPUnit\Framework\TestCase;
use RacingCar\TextConverter\HtmlTextConverter;

class HtmlTextConverterTest extends TestCase
{
    protected function getStubPath(): string
    {
        return __DIR__ . '/Stubs/text_stub.txt';
    }

    protected function tearDown(): void
    {
        file_put_contents($this->getStubPath(), '');

        parent::tearDown();
    }

    /** @test */
    public function it_opens_a_correct_text_file(): void
    {
        $converter = new HtmlTextConverter($this->getStubPath());

        $this->assertSame($this->getStubPath(), $converter->getFileName());
    }

    /** @test */
    public function it_adds_break_element_to_each_line(): void
    {
        $initial = <<<EOT
            1 bottle of beer on the wall
            1 bottle of beer
            Take one down and pass it around
            No more bottles of beer on the wall
            
            EOT;

        file_put_contents($this->getStubPath(), $initial);

        $converter = new HtmlTextConverter($this->getStubPath());

        $this->assertSame(
            '1 bottle of beer on the wall<br />1 bottle of beer<br />Take one down and pass it around<br />No more bottles of beer on the wall<br />',
            $converter->convertToHtml()
        );
    }

    /** @test */
    public function it_handles_html_characters(): void
    {
        $initial = <<<EOT
            <span>1 bottle of beer on the wall<span>
            1 bottle of beer
            Take one down and pass it around
            No more bottles of beer on the wall
            
            EOT;

        file_put_contents($this->getStubPath(), $initial);

        $converter = new HtmlTextConverter($this->getStubPath());

        $this->assertSame(
            '&lt;span&gt;1 bottle of beer on the wall&lt;span&gt;<br />1 bottle of beer<br />Take one down and pass it around<br />No more bottles of beer on the wall<br />',
            $converter->convertToHtml()
        );
    }
}
