<?php

namespace Tests;

use Core\HtmlMinifier;
use PHPUnit\Framework\TestCase;

class HtmlMinifierTest extends TestCase
{
    public function testItCollapsesWhitespace(): void
    {
        $html = "<div>\n    <p>Hello\n\t world</p>\n</div>";

        $this->assertSame('<div> <p>Hello world</p> </div>', HtmlMinifier::minify($html));
    }

    public function testItRemovesComments(): void
    {
        $html = "<p>a</p><!-- multi\nline comment --><p>b</p>";

        $this->assertSame('<p>a</p><p>b</p>', HtmlMinifier::minify($html));
    }

    public function testItKeepsScriptUntouched(): void
    {
        // single line "//" comment used to comment out the rest of the script after newlines were removed
        $script = "<script>\n// comment\nvar a = 1;\n</script>";

        $this->assertSame('<p>x</p> ' . $script, HtmlMinifier::minify("<p>x</p>\n\n" . $script));
    }

    public function testItKeepsPreAndTextareaUntouched(): void
    {
        $html = "<pre>  a\n  b</pre>\n<textarea>line 1\n\nline 2</textarea>";

        $this->assertSame("<pre>  a\n  b</pre> <textarea>line 1\n\nline 2</textarea>", HtmlMinifier::minify($html));
    }

    public function testItMatchesProtectedTagsCaseInsensitive(): void
    {
        $html = "<STYLE>\n  p { color: red; }\n</STYLE>";

        $this->assertSame($html, HtmlMinifier::minify($html));
    }

    public function testItRestoresManyProtectedBlocksInOrder(): void
    {
        // more than 10 blocks, so placeholders 1 and 10 must not be mixed up
        $html = '';
        $expected = '';
        for ($i = 0; $i < 12; $i++) {
            $html .= "<pre>{$i}\n</pre>\n";
            $expected .= "<pre>{$i}\n</pre> ";
        }

        $this->assertSame($expected, HtmlMinifier::minify($html));
    }
}
