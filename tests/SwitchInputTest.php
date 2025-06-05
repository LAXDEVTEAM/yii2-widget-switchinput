<?php

namespace kartik\switchinput\tests;

use PHPUnit\Framework\TestCase;
use kartik\switchinput\SwitchInput;
use yii\web\View;
use yii\base\InvalidConfigException;
use yii\helpers\Html;

/**
 * Test cases for SwitchInput widget
 */
class SwitchInputTest extends TestCase
{
    /**
     * @var SwitchInput|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $widget;

    /**
     * @var View|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $view;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock the View object
        $this->view = $this->createMock(View::class);

        // Create a partial mock of SwitchInput to avoid actual Yii2 dependencies
        $this->widget = $this->getMockBuilder(SwitchInput::class)
            ->onlyMethods(['getView', 'registerPlugin', 'getInput', 'hasModel'])
            ->getMock();

        $this->widget->method('getView')->willReturn($this->view);
        $this->widget->method('hasModel')->willReturn(false);
    }

    public function testDefaultConfiguration()
    {
        $this->assertEquals(SwitchInput::CHECKBOX, $this->widget->type);
        $this->assertFalse($this->widget->tristate);
        $this->assertNull($this->widget->indeterminateValue);
        $this->assertEquals([], $this->widget->indeterminateToggle);
        $this->assertEquals([], $this->widget->items);
        $this->assertTrue($this->widget->inlineLabel);
        $this->assertEquals([], $this->widget->itemOptions);
        $this->assertEquals([], $this->widget->labelOptions);
        $this->assertEquals(" &nbsp;", $this->widget->separator);
        $this->assertEquals(['class' => 'form-group'], $this->widget->containerOptions);
        $this->assertEquals('bootstrapSwitch', $this->widget->pluginName);
    }

    public function testCheckboxType()
    {
        $this->widget->type = SwitchInput::CHECKBOX;
        $this->widget->options = ['id' => 'test-switch', 'name' => 'test'];
        $this->widget->containerOptions = ['class' => 'form-group'];
        $this->widget->inlineLabel = true;
        $this->widget->tristate = false;

        $this->widget->method('getInput')->willReturn('<input type="checkbox" id="test-switch" name="test">');

        // Mock registerAssets to avoid actual asset registration
        $this->widget->method('registerPlugin')->willReturn(null);

        // Test the type is correctly set
        $this->assertEquals(SwitchInput::CHECKBOX, $this->widget->type);
    }

    public function testRadioType()
    {
        $this->widget->type = SwitchInput::RADIO;
        $this->widget->items = [
            ['label' => 'Option 1', 'value' => '1'],
            ['label' => 'Option 2', 'value' => '2']
        ];
        $this->widget->name = 'test-radio';
        $this->widget->value = '1';
        $this->widget->containerOptions = ['class' => 'form-group'];
        $this->widget->itemOptions = [];
        $this->widget->labelOptions = [];
        $this->widget->separator = " &nbsp;";
        $this->widget->inlineLabel = true;

        $this->assertEquals(SwitchInput::RADIO, $this->widget->type);
        $this->assertEquals(2, count($this->widget->items));
    }

    public function testTristateCheckbox()
    {
        $this->widget->type = SwitchInput::CHECKBOX;
        $this->widget->tristate = true;
        $this->widget->indeterminateValue = null;
        $this->widget->value = null;
        $this->widget->options = ['id' => 'tristate-test', 'name' => 'tristate'];
        $this->widget->containerOptions = ['class' => 'form-group'];
        $this->widget->inlineLabel = true;
        $this->widget->indeterminateToggle = [];

        $this->assertTrue($this->widget->tristate);
        $this->assertNull($this->widget->indeterminateValue);
    }

    public function testTristateCheckboxWithCustomIndeterminateValue()
    {
        $this->widget->type = SwitchInput::CHECKBOX;
        $this->widget->tristate = true;
        $this->widget->indeterminateValue = 2;
        $this->widget->value = 2;
        $this->widget->options = ['id' => 'tristate-custom-test', 'name' => 'tristate-custom'];
        $this->widget->containerOptions = ['class' => 'form-group'];
        $this->widget->inlineLabel = true;
        $this->widget->indeterminateToggle = [];
        $this->widget->pluginOptions = [];
        $this->widget->disabled = false;
        $this->widget->readonly = false;

        // Test configuration
        $this->assertTrue($this->widget->tristate);
        $this->assertEquals(2, $this->widget->indeterminateValue);
        $this->assertEquals(2, $this->widget->value);

        // Test registerAssets to verify indeterminate state is detected
        $this->widget->registerAssets();

        // When value equals indeterminateValue (2), indeterminate should be true
        $this->assertTrue($this->widget->pluginOptions['indeterminate']);
    }

    public function testInvalidTypeThrowsException()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage("You must define a valid 'type' which must be either 1 (for checkbox) or 2 (for radio).");

        $this->widget->type = 3; // Invalid type
        $this->widget->run();
    }

    public function testRadioWithoutItemsThrowsException()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage("You must setup the 'items' array for the 'radio' type.");

        $this->widget->type = SwitchInput::RADIO;
        $this->widget->items = [];
        $this->widget->run();
    }

    public function testRadioWithInvalidItemsThrowsException()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage("You must setup the 'items' array for the 'radio' type.");

        $this->widget->type = SwitchInput::RADIO;
        $this->widget->items = "invalid";
        $this->widget->run();
    }

    public function testRenderInputCheckbox()
    {
        $this->widget->type = SwitchInput::CHECKBOX;
        $this->widget->options = ['id' => 'test-checkbox', 'name' => 'test', 'label' => null];
        $this->widget->containerOptions = ['class' => 'form-group'];
        $this->widget->inlineLabel = true;
        $this->widget->tristate = false;
        $this->widget->indeterminateToggle = false;

        $this->widget->method('getInput')->willReturn('<input type="checkbox" id="test-checkbox" name="test">');

        $reflection = new \ReflectionClass($this->widget);
        $method = $reflection->getMethod('renderInput');
        $method->setAccessible(true);

        $output = $method->invoke($this->widget);

        $this->assertStringContainsString('<div class="form-group">', $output);
        $this->assertStringContainsString('<input type="checkbox"', $output);
    }

    public function testRenderInputTristateCheckbox()
    {
        $this->widget->type = SwitchInput::CHECKBOX;
        $this->widget->options = ['id' => 'tristate-checkbox', 'name' => 'tristate', 'label' => null];
        $this->widget->containerOptions = ['class' => 'form-group'];
        $this->widget->inlineLabel = true;
        $this->widget->tristate = true;
        $this->widget->value = 1;
        $this->widget->indeterminateToggle = [];
        $this->widget->name = 'tristate';

        $this->widget->method('hasModel')->willReturn(false);

        $reflection = new \ReflectionClass($this->widget);
        $method = $reflection->getMethod('renderInput');
        $method->setAccessible(true);

        $output = $method->invoke($this->widget);

        $this->assertStringContainsString('<div class="form-group">', $output);
        $this->assertStringContainsString('type="hidden"', $output);
        $this->assertStringContainsString('_dummy', $output);
    }

    public function testRenderInputRadio()
    {
        $this->widget->type = SwitchInput::RADIO;
        $this->widget->items = [
            ['label' => 'Option 1', 'value' => '1'],
            ['label' => 'Option 2', 'value' => '2', 'options' => ['class' => 'custom-option']],
            ['label' => false, 'value' => '3']
        ];
        $this->widget->name = 'test-radio';
        $this->widget->value = '1';
        $this->widget->containerOptions = ['class' => 'form-group'];
        $this->widget->itemOptions = [];
        $this->widget->labelOptions = [];
        $this->widget->separator = " &nbsp;";
        $this->widget->inlineLabel = true;

        $reflection = new \ReflectionClass($this->widget);
        $method = $reflection->getMethod('renderInput');
        $method->setAccessible(true);

        $output = $method->invoke($this->widget);

        $this->assertStringContainsString('<div class="form-group kv-switch-container">', $output);
        $this->assertStringContainsString('type="radio"', $output);
        $this->assertStringContainsString('value="1"', $output);
        $this->assertStringContainsString('value="2"', $output);
        $this->assertStringContainsString('&nbsp;', $output);
    }

    public function testMergeIndToggleDisabled()
    {
        $this->widget->tristate = false;
        $this->widget->indeterminateToggle = false;

        $reflection = new \ReflectionClass($this->widget);
        $method = $reflection->getMethod('mergeIndToggle');
        $method->setAccessible(true);

        $output = 'test output';
        $result = $method->invoke($this->widget, $output);

        $this->assertEquals($output, $result);
    }

    public function testMergeIndToggleEnabled()
    {
        $this->widget->tristate = true;
        $this->widget->indeterminateToggle = ['label' => 'X'];
        $this->widget->type = SwitchInput::CHECKBOX;
        $this->widget->options = ['id' => 'test-switch'];
        $this->widget->pluginOptions = ['size' => 'small'];

        $reflection = new \ReflectionClass($this->widget);
        $method = $reflection->getMethod('mergeIndToggle');
        $method->setAccessible(true);

        $output = 'test output';
        $result = $method->invoke($this->widget, $output);

        $this->assertStringContainsString('data-kv-switch="test-switch"', $result);
        $this->assertStringContainsString('kv-ind-container', $result);
        $this->assertStringContainsString('kv-size-small', $result);
    }

    public function testRegisterAssets()
    {
        $this->widget->pluginOptions = [];
        $this->widget->tristate = false;
        $this->widget->value = '1';
        $this->widget->indeterminateValue = null;
        $this->widget->type = SwitchInput::CHECKBOX;
        $this->widget->disabled = false;
        $this->widget->readonly = false;
        $this->widget->options = ['id' => 'test-switch'];
        $this->widget->pluginName = 'bootstrapSwitch';

        // Call the method (asset registration is mocked in our mock classes)
        $this->widget->registerAssets();

        // Test that plugin options are set correctly
        $this->assertTrue($this->widget->pluginOptions['animate']);
        $this->assertFalse($this->widget->pluginOptions['indeterminate']);
        $this->assertFalse($this->widget->pluginOptions['disabled']);
        $this->assertFalse($this->widget->pluginOptions['readonly']);
    }

    public function testRegisterAssetsTristate()
    {
        $this->widget->pluginOptions = [];
        $this->widget->tristate = true;
        $this->widget->value = null;
        $this->widget->indeterminateValue = null;
        $this->widget->type = SwitchInput::CHECKBOX;
        $this->widget->disabled = false;
        $this->widget->readonly = false;
        $this->widget->options = ['id' => 'tristate-test'];
        $this->widget->pluginName = 'bootstrapSwitch';
        $this->widget->indeterminateToggle = [];

        $this->view->expects($this->once())
            ->method('registerJs')
            ->with($this->isType('string'));

        $this->widget->registerAssets();

        $this->assertTrue($this->widget->pluginOptions['indeterminate']);
    }

    public function testPluginOptionsDefaults()
    {
        $this->widget->pluginOptions = ['animate' => false];
        $this->widget->tristate = false;
        $this->widget->value = '0';
        $this->widget->indeterminateValue = null;
        $this->widget->type = SwitchInput::CHECKBOX;
        $this->widget->disabled = true;
        $this->widget->readonly = true;
        $this->widget->options = ['id' => 'test-switch'];

        $this->widget->registerAssets();

        $this->assertFalse($this->widget->pluginOptions['animate']);
        $this->assertTrue($this->widget->pluginOptions['disabled']);
        $this->assertTrue($this->widget->pluginOptions['readonly']);
    }

    public function testConstantsDefinition()
    {
        $this->assertEquals(1, SwitchInput::CHECKBOX);
        $this->assertEquals(2, SwitchInput::RADIO);
    }

    public function testItemsConfiguration()
    {
        $items = [
            ['label' => 'Yes', 'value' => '1', 'options' => ['class' => 'option-yes']],
            ['label' => 'No', 'value' => '0', 'labelOptions' => ['class' => 'label-no']],
            ['label' => null, 'value' => 'null']
        ];

        $this->widget->items = $items;
        $this->assertEquals(3, count($this->widget->items));
        $this->assertEquals('Yes', $this->widget->items[0]['label']);
        $this->assertEquals('1', $this->widget->items[0]['value']);
        $this->assertArrayHasKey('options', $this->widget->items[0]);
    }

    public function testInlineLabelConfiguration()
    {
        $this->widget->inlineLabel = false;
        $this->assertFalse($this->widget->inlineLabel);

        $this->widget->inlineLabel = true;
        $this->assertTrue($this->widget->inlineLabel);
    }

    public function testContainerOptionsConfiguration()
    {
        $options = ['class' => 'custom-container', 'data-test' => 'value'];
        $this->widget->containerOptions = $options;

        $this->assertEquals($options, $this->widget->containerOptions);
        $this->assertEquals('custom-container', $this->widget->containerOptions['class']);
        $this->assertEquals('value', $this->widget->containerOptions['data-test']);
    }

    public function testSeparatorConfiguration()
    {
        $separator = " | ";
        $this->widget->separator = $separator;

        $this->assertEquals($separator, $this->widget->separator);
    }

    public function testIndeterminateToggleConfiguration()
    {
        // Test with array configuration
        $config = ['label' => '?', 'class' => 'custom-toggle'];
        $this->widget->indeterminateToggle = $config;

        $this->assertEquals($config, $this->widget->indeterminateToggle);

        // Test with false (disabled)
        $this->widget->indeterminateToggle = false;
        $this->assertFalse($this->widget->indeterminateToggle);
    }
}
