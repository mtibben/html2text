<?php

/*
 * Copyright (c) 2005-2007 Jon Abernathy <jon@chuggnutt.com>
 *
 * This script is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

namespace Html2Text;

class Html2Text
{
    const ENCODING = 'UTF-8';

    /**
     * Contains the HTML content to convert.
     *
     * @type string
     */
    protected $html;

    /**
     * Contains the converted, formatted text.
     *
     * @type string
     */
    protected $text;

    /**
     * Maximum width of the formatted text, in columns.
     *
     * Set this value to 0 (or less) to ignore word wrapping
     * and not constrain text to a fixed-width column.
     *
     * @type integer
     */
    protected $width = 70;

    protected $searchArray = array(
        "/\r/" => "",                                                       // Non-legal carriage return
        "/[\n\t]+/" => " ",                                                 // Newlines and tabs
        '/<head[^>]*>.*?<\/head>/i' => '',                                  // <head>
        '/<script[^>]*>.*?<\/script>/i' => '',                              // <script>s -- which strip_tags supposedly has problems with
        '/<style[^>]*>.*?<\/style>/i' => '',                                // <style>s -- which strip_tags supposedly has problems with
        '/<p[^>]*>/i' => "\n\n",                                            // <P>
        '/<br[^>]*>/i' => "\n",                                             // <br>
        '/<i[^>]*>(.*?)<\/i>/i' => '_\\1_',                                 // <i>
        '/<em[^>]*>(.*?)<\/em>/i' => '_\\1_',                               // <em>
        '/(<ul[^>]*>|<\/ul>)/i' => "\n\n",                                  // <ul> and </ul>
        '/(<ol[^>]*>|<\/ol>)/i' => "\n\n",                                  // <ol> and </ol>
        '/(<dl[^>]*>|<\/dl>)/i' => "\n\n",                                  // <dl> and </dl>
        '/<li[^>]*>(.*?)<\/li>/i' => "\t* \\1\n",                           // <li> and </li>
        '/<dd[^>]*>(.*?)<\/dd>/i' => " \\1\n",                              // <dd> and </dd>
        '/<dt[^>]*>(.*?)<\/dt>/i' => "\t* \\1",                             // <dt> and </dt>
        '/<li[^>]*>/i' => "\n\t* ",                                         // <li>
        '/<hr[^>]*>/i' => "\n-------------------------\n",                  // <hr>
        '/<div[^>]*>/i' => "<div>\n",                                       // <div>
        '/(<table[^>]*>|<\/table>)/i' => "\n\n",                            // <table> and </table>
        '/(<tr[^>]*>|<\/tr>)/i' => "\n",                                    // <tr> and </tr>
        '/<td[^>]*>(?:&nbsp;|&emsp;|&ensp;|&thinsp;){2,}<\/td>/i' => "",    // <td> containing blank spaces </td>
        '/<td[^>]*>(.*?)<\/td>/i' => "\t\t\\1\n",                           // <td> and </td>
        '/<span class="_html2text_ignore">.+?<\/span>/i' => ""              // <span class="_html2text_ignore">...</span>
    );

    /**
     * List of preg* regular expression patterns to search for,
     * used in conjunction with $entReplace.
     *
     * @type array
     * @see $entReplace
     */
    protected $entSearchArray = array(
        '/&#153;/i' => '™',                                      // TM symbol in win-1252
        '/&#151;/i' => '—',                                      // m-dash in win-1252
        '/&(amp|#38);/i' => '|+|amp|+|',                         // Ampersand: see converter()
        '/[ ]{2,}/' => ' ',                                      // Runs of spaces, post-handling
    );

    /**
     * List of preg* regular expression patterns to search for
     * and replace using callback function.
     *
     * @type array
     */
    protected $callbackSearch = array(
        '/<(a) [^>]*href=("|\')([^"\']+)\2([^>]*)>(.*?)<\/a>/i', // <a href="">
        '/<(h)[123456]( [^>]*)?>(.*?)<\/h[123456]>/i',           // h1 - h6
        '/<(b)( [^>]*)?>(.*?)<\/b>/i',                           // <b>
        '/<(strong)( [^>]*)?>(.*?)<\/strong>/i',                 // <strong>
        '/<(th)( [^>]*)?>(.*?)<\/th>/i',                         // <th> and </th>
    );

    /**
     * List of preg* regular expression patterns to search for in PRE body,
     * used in conjunction with $preReplace.
     *
     * @type array
     * @see $preReplace
     */
    protected $preSearchArray = array(
        "/\n/" => '<br>',
        "/\t/" => '&nbsp;&nbsp;&nbsp;&nbsp;',
        '/ /' =>  '&nbsp;',
        '/<pre[^>]*>/' => '',
        '/<\/pre>/' => '',
    );

    /**
     * Temporary workspace used during PRE processing.
     *
     * @type string
     */
    protected $preContent = '';

    /**
     * Contains the base URL that relative links should resolve to.
     *
     * @type string
     */
    protected $baseurl = '';

    /**
     * Indicates whether content in the $html variable has been converted yet.
     *
     * @type boolean
     * @see $html, $text
     */
    protected $converted = false;

    /**
     * Contains URL addresses from links to be rendered in plain text.
     *
     * @type array
     * @see buildlinkList()
     */
    protected $linkList = array();

    /**
     * Various configuration options (able to be set in the constructor)
     *
     * @type array
     */
    protected $options = array(
        'do_links' => 'inline', // 'none'
                                // 'inline' (show links inline)
                                // 'nextline' (show links on the next line)
                                // 'table' (if a table of link URLs should be listed after the text.

        'width' => 70,          //  Maximum width of the formatted text, in columns.
                                //  Set this value to 0 (or less) to ignore word wrapping
                                //  and not constrain text to a fixed-width column.
    );

    private function legacyConstruct($html = '', $fromFile = false, array $options = array())
    {
        $this->set_html($html, $fromFile);
        $this->options = array_merge($this->options, $options);
    }

    /**
     * @param string $html    Source HTML
     * @param array  $options Set configuration options
     */
    public function __construct($html = '', $options = array())
    {
        // for backwards compatibility
        if (!is_array($options)) {
            return call_user_func_array(array($this, 'legacyConstruct'), func_get_args());
        }

        $this->html = $html;
        $this->options = array_merge($this->options, $options);
    }

    /**
     * Set the source HTML
     *
     * @param string $html HTML source content
     */
    public function setHtml($html)
    {
        $this->html = $html;
        $this->converted = false;
    }

    /**
     * @deprecated
     */
    public function set_html($html, $from_file = false)
    {
        if ($from_file) {
            throw new \InvalidArgumentException("Argument from_file no longer supported");
        }

        return $this->setHtml($html);
    }

    /**
     * Returns the text, converted from HTML.
     *
     * @return string
     */
    public function getText()
    {
        if (!$this->converted) {
            $this->convert();
        }

        return $this->text;
    }

    /**
     * @deprecated
     */
    public function get_text()
    {
        return $this->getText();
    }

    /**
     * @deprecated
     */
    public function print_text()
    {
        print $this->getText();
    }

    /**
     * @deprecated
     */
    public function p()
    {
        return $this->print_text();
    }

    /**
     * Sets a base URL to handle relative links.
     *
     * @param string $baseurl
     */
    public function setBaseUrl($baseurl)
    {
        $this->baseurl = $baseurl;
    }

    /**
     * @deprecated
     */
    public function set_base_url($baseurl)
    {
        return $this->setBaseUrl($baseurl);
    }

    protected function convert()
    {
        $this->linkList = array();

        $text = trim(stripslashes($this->html));

        $this->converter($text);

        if ($this->linkList) {
            $text .= "\n\nLinks:\n------\n";
            foreach ($this->linkList as $i => $url) {
                $text .= '[' . ($i + 1) . '] ' . $url . "\n";
            }
        }

        $this->text = $text;

        $this->converted = true;
    }

    protected function converter(&$text)
    {
        $this->convertBlockquotes($text);
        $this->convertPre($text);
        $text = preg_replace(array_keys($this->searchArray), array_values($this->searchArray), $text);
        $text = preg_replace_callback($this->callbackSearch, array($this, 'pregCallback'), $text);
        $text = strip_tags($text);
        $text = preg_replace(array_keys($this->entSearchArray), array_values($this->entSearchArray), $text);
        $text = preg_replace("/(&nbsp;){2,}|(&emsp;){2,}|(&ensp;){2,}|(&thinsp;){2,}/", "\t", $text); // Replace more than two blank spaces with a tab.
	    $text = preg_replace("/(&nbsp;){1}|(&emsp;){1}|(&ensp;){1}|(&thinsp;){1}/", " ", $text); // Remove any leftover single spaces and replace with a space
        $text = html_entity_decode($text, ENT_QUOTES, self::ENCODING);

        // Remove unknown/unhandled entities (this cannot be done in search-and-replace block)
        $text = preg_replace('/&([a-zA-Z0-9]{2,6}|#[0-9]{2,4});/', '', $text);

        // Convert "|+|amp|+|" into "&", need to be done after handling of unknown entities
        // This properly handles situation of "&amp;quot;" in input string
        $text = str_replace('|+|amp|+|', '&', $text);

        // Normalise empty lines
        $text = preg_replace("/\n\s+\n/", "\n\n", $text);
        $text = preg_replace("/[\n]{3,}/", "\n\n", $text);

        // remove leading empty lines (can be produced by eg. P tag on the beginning)
        $text = ltrim($text, "\n");

        if ($this->options['width'] > 0) {
            $text = wordwrap($text, $this->options['width']);
        }
    }

    /**
     * Helper function called by preg_replace() on link replacement.
     *
     * Maintains an internal list of links to be displayed at the end of the
     * text, with numeric indices to the original point in the text they
     * appeared. Also makes an effort at identifying and handling absolute
     * and relative links.
     *
     * @param  string $link          URL of the link
     * @param  string $display       Part of the text to associate number with
     * @param  null   $linkOverride
     * @return string
     */
    protected function buildlinkList($link, $display, $linkOverride = null)
    {
        $linkMethod = ($linkOverride) ? $linkOverride : $this->options['do_links'];
        if ($linkMethod == 'none') {
            return $display;
        }

        // Ignored link types
        if (preg_match('!^(javascript:|mailto:|#)!i', $link)) {
            return $display;
        }

        if (preg_match('!^([a-z][a-z0-9.+-]+:)!i', $link)) {
            $url = $link;
        } else {
            $url = $this->baseurl;
            if (substr($link, 0, 1) != '/') {
                $url .= '/';
            }
            $url .= $link;
        }

        if ($linkMethod == 'table') {
            if (($index = array_search($url, $this->linkList)) === false) {
                $index = count($this->linkList);
                $this->linkList[] = $url;
            }

            return $display . ' [' . ($index + 1) . ']';
        } elseif ($linkMethod == 'nextline') {
            return $display . "\n[" . $url . ']';
        } else { // link_method defaults to inline
            return $display . ' [' . $url . ']';
        }
    }

    protected function convertPre(&$text)
    {
        // get the content of PRE element
        while (preg_match('/<pre[^>]*>(.*)<\/pre>/ismU', $text, $matches)) {
            $this->preContent = $matches[1];

            // Run our defined tags search-and-replace with callback
            $this->preContent = preg_replace_callback(
                $this->callbackSearch,
                array($this, 'pregCallback'),
                $this->preContent
            );

            // convert the content
            $this->preContent = sprintf(
                '<div><br>%s<br></div>',
                preg_replace(array_keys($this->preSearchArray), array_values($this->preSearchArray), $this->preContent)
            );

            // replace the content (use callback because content can contain $0 variable)
            $text = preg_replace_callback(
                '/<pre[^>]*>.*<\/pre>/ismU',
                array($this, 'pregPreCallback'),
                $text,
                1
            );

            // free memory
            $this->preContent = '';
        }
    }

    /**
     * Helper function for BLOCKQUOTE body conversion.
     *
     * @param string $text HTML content
     */
    protected function convertBlockquotes(&$text)
    {
        if (preg_match_all('/<\/*blockquote[^>]*>/i', $text, $matches, PREG_OFFSET_CAPTURE)) {
            $start = 0;
            $taglen = 0;
            $level = 0;
            $diff = 0;
            foreach ($matches[0] as $m) {
                if ($m[0][0] == '<' && $m[0][1] == '/') {
                    $level--;
                    if ($level < 0) {
                        $level = 0; // malformed HTML: go to next blockquote
                    } elseif ($level > 0) {
                        // skip inner blockquote
                    } else {
                        $end = $m[1];
                        $len = $end - $taglen - $start;
                        // Get blockquote content
                        $body = substr($text, $start + $taglen - $diff, $len);

                        // Set text width
                        $pWidth = $this->options['width'];
                        if ($this->options['width'] > 0) $this->options['width'] -= 2;
                        // Convert blockquote content
                        $body = trim($body);
                        $this->converter($body);
                        // Add citation markers and create PRE block
                        $body = preg_replace('/((^|\n)>*)/', '\\1> ', trim($body));
                        $body = '<pre>' . htmlspecialchars($body) . '</pre>';
                        // Re-set text width
                        $this->options['width'] = $pWidth;
                        // Replace content
                        $text = substr($text, 0, $start - $diff)
                            . $body . substr($text, $end + strlen($m[0]) - $diff);

                        $diff = $len + $taglen + strlen($m[0]) - strlen($body);
                        unset($body);
                    }
                } else {
                    if ($level == 0) {
                        $start = $m[1];
                        $taglen = strlen($m[0]);
                    }
                    $level++;
                }
            }
        }
    }

    /**
     * Callback function for preg_replace_callback use.
     *
     * @param  array  $matches PREG matches
     * @return string
     */
    protected function pregCallback($matches)
    {
        switch (strtolower($matches[1])) {
            case 'b':
            case 'strong':
                return $this->toupper($matches[3]);
            case 'th':
                return $this->toupper("\t\t" . $matches[3] . "\n");
            case 'h':
                return $this->toupper("\n\n" . $matches[3] . "\n\n");
            case 'a':
                // override the link method
                $linkOverride = null;
                if (preg_match('/_html2text_link_(\w+)/', $matches[4], $linkOverrideMatch)) {
                    $linkOverride = $linkOverrideMatch[1];
                }
                // Remove spaces in URL (#1487805)
                $url = str_replace(' ', '', $matches[3]);

                return $this->buildlinkList($url, $matches[5], $linkOverride);
        }

        return '';
    }

    /**
     * Callback function for preg_replace_callback use in PRE content handler.
     *
     * @param  array  $matches PREG matches
     * @return string
     */
    protected function pregPreCallback(/** @noinspection PhpUnusedParameterInspection */ $matches)
    {
        return $this->preContent;
    }

    /**
     * Strtoupper function with HTML tags and entities handling.
     *
     * @param  string $str Text to convert
     * @return string Converted text
     */
    private function toupper($str)
    {
        // string can contain HTML tags
        $chunks = preg_split('/(<[^>]*>)/', $str, null, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        // convert toupper only the text between HTML tags
        foreach ($chunks as $i => $chunk) {
            if ($chunk[0] != '<') {
                $chunks[$i] = $this->strtoupper($chunk);
            }
        }

        return implode($chunks);
    }

    /**
     * Strtoupper multibyte wrapper function with HTML entities handling.
     *
     * @param  string $str Text to convert
     * @return string Converted text
     */
    private function strtoupper($str)
    {
        $str = html_entity_decode($str, ENT_COMPAT, self::ENCODING);

        if (function_exists('mb_strtoupper')) {
            $str = mb_strtoupper($str, self::ENCODING);
        } else {
            $str = strtoupper($str);
        }

        $str = htmlspecialchars($str, ENT_COMPAT, self::ENCODING);

        return $str;
    }
}