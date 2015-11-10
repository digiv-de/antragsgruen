<?php

namespace unit;

use app\components\diff\DiffRenderer;
use app\components\HTMLTools;
use Codeception\Specify;

class DiffRendererTest extends TestBase
{
    use Specify;

    /**
     */
    public function testSplitText()
    {
        $renderer = new DiffRenderer();
        list($nodes, $inIns, $inDel) = $renderer->textToNodes(
            'Test1###INS_START###Inserted###INS_END###Bla###INS_START###Inserted###INS_END###Bla###DEL_START###Deleted###DEL_END###Ende',
            false,
            false,
            null
        );
        $this->assertEquals(7, count($nodes));
        $this->assertEquals('ins', $nodes[1]->nodeName);
        $this->assertEquals('Inserted', $nodes[1]->childNodes[0]->nodeValue);
        $this->assertEquals('Bla', $nodes[2]->nodeValue);
        $this->assertEquals('ins', $nodes[3]->nodeName);
        $this->assertEquals('del', $nodes[5]->nodeName);
        $this->assertEquals('Ende', $nodes[6]->nodeValue);
        $this->assertEquals(false, $inIns);
        $this->assertEquals(false, $inDel);

        $renderer = new DiffRenderer();
        list($nodes, $inIns, $inDel) = $renderer->textToNodes(
            'Test1###INS_START###Inserted###INS_END###Bla###INS_START###Inserted###INS_END###Bla###DEL_START###Deleted',
            true,
            false,
            null
        );

        $this->assertEquals(5, count($nodes));
        $this->assertEquals('ins', $nodes[0]->nodeName);
        $this->assertEquals('Test1###INS_START###Inserted', $nodes[0]->childNodes[0]->nodeValue);
        $this->assertEquals('Bla', $nodes[1]->nodeValue);
        $this->assertEquals('del', $nodes[4]->nodeName);
        $this->assertEquals(false, $inIns);
        $this->assertEquals(true, $inDel);
    }

    /**
     */
    public function testLevel1()
    {
        $renderer = new DiffRenderer();

        $html     = '<p>Test 1 ###INS_START###Inserted ###INS_END###Test 2</p>';
        $rendered = $renderer->renderHtmlWithPlaceholders($html);
        $this->assertEquals('<p>Test 1 <ins>Inserted </ins>Test 2</p>', $rendered);

        $html     = '<p>Test 1 ###INS_START###Inserted Test 2</p>';
        $rendered = $renderer->renderHtmlWithPlaceholders($html);
        $this->assertEquals('<p>Test 1 <ins>Inserted Test 2</ins></p>', $rendered);

        $html     = '<p>Test 1 ###DEL_START###Deleted ###DEL_END###Test 2</p>';
        $rendered = $renderer->renderHtmlWithPlaceholders($html);
        $this->assertEquals('<p>Test 1 <del>Deleted </del>Test 2</p>', $rendered);

        $html     = '<p>Test 1 ###DEL_START###Deleted Test 2</p>';
        $rendered = $renderer->renderHtmlWithPlaceholders($html);
        $this->assertEquals('<p>Test 1 <del>Deleted Test 2</del></p>', $rendered);
    }

    /**
     */
    public function testLevel2()
    {
        $renderer = new DiffRenderer();

        $html     = '<p>Test 1 <strong>Fett</strong> ###DEL_START###Deleted Test 2</p>';
        $rendered = $renderer->renderHtmlWithPlaceholders($html);
        $this->assertEquals('<p>Test 1 <strong>Fett</strong> <del>Deleted Test 2</del></p>', $rendered);

        $html     = '<p>Test 1 ###DEL_START###Deleted <strong>Fett</strong> Test 2</p>';
        $rendered = $renderer->renderHtmlWithPlaceholders($html);
        $this->assertEquals('<p>Test 1 <del>Deleted <strong>Fett</strong> Test 2</del></p>', $rendered);

        $html     = '<p>Test 1 ###INS_START###Inserted <strong>Fett</strong> Test 2</p>';
        $rendered = $renderer->renderHtmlWithPlaceholders($html);
        $this->assertEquals('<p>Test 1 <ins>Inserted <strong>Fett</strong> Test 2</ins></p>', $rendered);

        $html     = '<ul><li>Test 1###INS_START###</li><li>Neuer Punkt</li><li>###INS_END###Test 2</li></ul>';
        $rendered = $renderer->renderHtmlWithPlaceholders($html);
        $this->assertEquals('<ul><li>Test 1</li><li class="inserted">Neuer Punkt</li><li>Test 2</li></ul>', $rendered);

        $html     = '<ul><li>Test 1###DEL_START###</li><li>Gelöschter Punkt</li><li>###DEL_END###Test 2</li></ul>';
        $rendered = $renderer->renderHtmlWithPlaceholders($html);
        $this->assertEquals('<ul><li>Test 1</li><li class="deleted">Gelöschter Punkt</li><li>Test 2</li></ul>', $rendered);

        $html     = '<ul><li>Test 1###INS_START###23</li><li>Neuer Punkt</li><li>Start###INS_END###Test 2</li></ul>';
        $rendered = $renderer->renderHtmlWithPlaceholders($html);
        $this->assertEquals('<ul><li>Test 1<ins>23</ins></li><li class="inserted">Neuer Punkt</li><li><ins>Start</ins>Test 2</li></ul>', $rendered);

        $html     = '<p>Test 123<strong> Alt###INS_START###</strong> Neu normal<strong>Neu fett ###INS_END###alt</strong> Ende</p>';
        $rendered = $renderer->renderHtmlWithPlaceholders($html);
        $this->assertEquals('<p>Test 123<strong> Alt</strong><ins> Neu normal</ins><strong><ins>Neu fett </ins>alt</strong> Ende</p>', $rendered);
    }

    /**
     */
    public function testLevel3()
    {
        $renderer = new DiffRenderer();

        $html     = '<ul><li>Test###INS_START###<p>Neuer Absatz</p>###INS_END###.</li></ul>';
        $rendered = $renderer->renderHtmlWithPlaceholders($html);
        $this->assertEquals('<ul><li>Test<p class="inserted">Neuer Absatz</p>.</li></ul>', $rendered);
    }
}