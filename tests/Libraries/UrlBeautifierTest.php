<?php

use App\Libraries\UrlBeautifier;

class UrlBeautifierTest extends TestCase {
    /**
     * Tests if multiple spaces are replaced by a single hyphen.
     *
     * @return void
     */
    public function test_if_spaces_are_replaced_by_hyphen()
    {
        $input = "some    string";
        $expected = "some-string";

        $output = UrlBeautifier::beautify($input);
        $this->assertSame($output, $expected);
    }

    /**
     * Tests if html entities are replaced correctly.
     *
     * @return void
     */
    public function test_if_html_entities_are_replaced()
    {
        $input = "&#368;&ouml;";
        $expected = "Uo";

        $output = UrlBeautifier::beautify($input);
        $this->assertSame($output, $expected);
    }

    /**
     * Tests if hungarian characters are replaced correctly.
     *
     * @return void
     */
    public function test_if_hungarian_characters_are_replaced()
    {
        $input = "ÁRVÍZTŰRŐTÜKÖRFÚRÓGÉP";
        $expected = "ARVIZTUROTUKORFUROGEP";

        $output = UrlBeautifier::beautify($input);
        $this->assertSame($output, $expected);
    }

    /**
     * Tests if other characters are trimmed correctly.
     *
     * @return void
     */
    public function test_if_other_characters_are_trimmed()
    {
        $input = "\tsome text,!?#";
        $expected = "some-text";

        $output = UrlBeautifier::beautify($input);
        $this->assertSame($output, $expected);
    }
}
