<?php

namespace kartik\switchinput\tests\functional;

use PHPUnit\Framework\TestCase;
use kartik\switchinput\SwitchInput;

/**
 * Functional test cases for SwitchInput widget
 * These tests focus on testing the widget behavior without heavy mocking
 */
class SwitchInputFunctionalTest extends TestCase
{
    public function testWidgetConstants()
    {
        $this->assertEquals(1, SwitchInput::CHECKBOX);
        $this->assertEquals(2, SwitchInput::RADIO);
    }

    public function testDefaultProperties()
    {
        $widget = new SwitchInput();

        $this->assertEquals(SwitchInput::CHECKBOX, $widget->type);
        $this->assertFalse($widget->tristate);
        $this->assertNull($widget->indeterminateValue);
        $this->assertEquals([], $widget->indeterminateToggle);
        $this->assertEquals([], $widget->items);
        $this->assertTrue($widget->inlineLabel);
        $this->assertEquals([], $widget->itemOptions);
        $this->assertEquals([], $widget->labelOptions);
        $this->assertEquals(" &nbsp;", $widget->separator);
        $this->assertEquals(['class' => 'form-group'], $widget->containerOptions);
        $this->assertEquals('bootstrapSwitch', $widget->pluginName);
    }

    public function testTypeAssignment()
    {
        $widget = new SwitchInput();

        $widget->type = SwitchInput::CHECKBOX;
        $this->assertEquals(SwitchInput::CHECKBOX, $widget->type);

        $widget->type = SwitchInput::RADIO;
        $this->assertEquals(SwitchInput::RADIO, $widget->type);
    }

    public function testTristateConfiguration()
    {
        $widget = new SwitchInput();

        $widget->tristate = true;
        $this->assertTrue($widget->tristate);

        $widget->tristate = false;
        $this->assertFalse($widget->tristate);
    }

    public function testIndeterminateValueConfiguration()
    {
        $widget = new SwitchInput();

        $widget->indeterminateValue = null;
        $this->assertNull($widget->indeterminateValue);

        $widget->indeterminateValue = 'indeterminate';
        $this->assertEquals('indeterminate', $widget->indeterminateValue);

        $widget->indeterminateValue = 0;
        $this->assertEquals(0, $widget->indeterminateValue);

        $widget->indeterminateValue = 2;
        $this->assertEquals(2, $widget->indeterminateValue);
    }

    public function testTristateWithCustomIndeterminateValue()
    {
        $widget = new SwitchInput();

        $widget->type = SwitchInput::CHECKBOX;
        $widget->tristate = true;
        $widget->indeterminateValue = 2;
        $widget->value = 2;

        $this->assertTrue($widget->tristate);
        $this->assertEquals(2, $widget->indeterminateValue);
        $this->assertEquals(2, $widget->value);

        // Test different value states
        $widget->value = 0;
        $this->assertEquals(0, $widget->value);

        $widget->value = 1;
        $this->assertEquals(1, $widget->value);

        $widget->value = 2; // Back to indeterminate
        $this->assertEquals(2, $widget->value);
        $this->assertEquals($widget->indeterminateValue, $widget->value);
    }

    public function testItemsConfiguration()
    {
        $widget = new SwitchInput();

        $items = [
            ['label' => 'Option 1', 'value' => '1'],
            ['label' => 'Option 2', 'value' => '2'],
            ['label' => 'Option 3', 'value' => '3', 'options' => ['disabled' => true]]
        ];

        $widget->items = $items;
        $this->assertEquals($items, $widget->items);
        $this->assertEquals(3, count($widget->items));
        $this->assertEquals('Option 1', $widget->items[0]['label']);
        $this->assertEquals('1', $widget->items[0]['value']);
        $this->assertTrue($widget->items[2]['options']['disabled']);
    }

    public function testInlineLabelConfiguration()
    {
        $widget = new SwitchInput();

        $widget->inlineLabel = true;
        $this->assertTrue($widget->inlineLabel);

        $widget->inlineLabel = false;
        $this->assertFalse($widget->inlineLabel);
    }

    public function testItemOptionsConfiguration()
    {
        $widget = new SwitchInput();

        $itemOptions = ['class' => 'custom-item', 'data-toggle' => 'switch'];
        $widget->itemOptions = $itemOptions;

        $this->assertEquals($itemOptions, $widget->itemOptions);
        $this->assertEquals('custom-item', $widget->itemOptions['class']);
        $this->assertEquals('switch', $widget->itemOptions['data-toggle']);
    }

    public function testLabelOptionsConfiguration()
    {
        $widget = new SwitchInput();

        $labelOptions = ['class' => 'custom-label', 'style' => 'font-weight: bold;'];
        $widget->labelOptions = $labelOptions;

        $this->assertEquals($labelOptions, $widget->labelOptions);
        $this->assertEquals('custom-label', $widget->labelOptions['class']);
        $this->assertEquals('font-weight: bold;', $widget->labelOptions['style']);
    }

    public function testSeparatorConfiguration()
    {
        $widget = new SwitchInput();

        $separator = ' | ';
        $widget->separator = $separator;

        $this->assertEquals($separator, $widget->separator);

        $separator = '<br>';
        $widget->separator = $separator;

        $this->assertEquals($separator, $widget->separator);
    }

    public function testContainerOptionsConfiguration()
    {
        $widget = new SwitchInput();

        $containerOptions = [
            'class' => 'custom-container',
            'id' => 'switch-container',
            'data-test' => 'value'
        ];
        $widget->containerOptions = $containerOptions;

        $this->assertEquals($containerOptions, $widget->containerOptions);
        $this->assertEquals('custom-container', $widget->containerOptions['class']);
        $this->assertEquals('switch-container', $widget->containerOptions['id']);
        $this->assertEquals('value', $widget->containerOptions['data-test']);
    }

    public function testIndeterminateToggleConfiguration()
    {
        $widget = new SwitchInput();

        // Test with array configuration
        $indeterminateToggle = [
            'label' => '×',
            'class' => 'custom-toggle',
            'containerOptions' => ['class' => 'toggle-container']
        ];
        $widget->indeterminateToggle = $indeterminateToggle;

        $this->assertEquals($indeterminateToggle, $widget->indeterminateToggle);
        $this->assertEquals('×', $widget->indeterminateToggle['label']);
        $this->assertEquals('custom-toggle', $widget->indeterminateToggle['class']);

        // Test with false (disabled)
        $widget->indeterminateToggle = false;
        $this->assertFalse($widget->indeterminateToggle);
    }

    public function testPluginNameConfiguration()
    {
        $widget = new SwitchInput();

        $this->assertEquals('bootstrapSwitch', $widget->pluginName);

        $widget->pluginName = 'customSwitch';
        $this->assertEquals('customSwitch', $widget->pluginName);
    }

    public function testMultiplePropertyAssignments()
    {
        $widget = new SwitchInput();

        $widget->type = SwitchInput::RADIO;
        $widget->tristate = true;
        $widget->indeterminateValue = 'maybe';
        $widget->inlineLabel = false;
        $widget->separator = ' - ';
        $widget->items = [
            ['label' => 'Yes', 'value' => 'yes'],
            ['label' => 'No', 'value' => 'no'],
            ['label' => 'Maybe', 'value' => 'maybe']
        ];

        $this->assertEquals(SwitchInput::RADIO, $widget->type);
        $this->assertTrue($widget->tristate);
        $this->assertEquals('maybe', $widget->indeterminateValue);
        $this->assertFalse($widget->inlineLabel);
        $this->assertEquals(' - ', $widget->separator);
        $this->assertEquals(3, count($widget->items));
        $this->assertEquals('Yes', $widget->items[0]['label']);
        $this->assertEquals('yes', $widget->items[0]['value']);
    }
}
