<?php

namespace kartik\switchinput\tests\unit;

use PHPUnit\Framework\TestCase;
use kartik\switchinput\SwitchInput;
use yii\base\InvalidConfigException;

/**
 * Test cases for SwitchInput widget exception handling and edge cases
 */
class ExceptionTest extends TestCase
{
    /**
     * @var SwitchInput|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $widget;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a mock widget to test exception scenarios
        $this->widget = $this->getMockBuilder(SwitchInput::class)
            ->onlyMethods(['registerAssets'])
            ->getMock();

        $this->widget->method('registerAssets')->willReturn(null);
    }

    /**
     * Test that invalid type (not 1 or 2) throws InvalidConfigException
     */
    public function testInvalidTypeThrowsException()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage("You must define a valid 'type' which must be either 1 (for checkbox) or 2 (for radio).");

        $this->widget->type = 3; // Invalid type
        $this->widget->run();
    }

    /**
     * Test that null type throws InvalidConfigException
     */
    public function testNullTypeThrowsException()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage("You must define a valid 'type' which must be either 1 (for checkbox) or 2 (for radio).");

        $this->widget->type = null;
        $this->widget->run();
    }

    /**
     * Test that string type throws InvalidConfigException
     */
    public function testStringTypeThrowsException()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage("You must define a valid 'type' which must be either 1 (for checkbox) or 2 (for radio).");

        $this->widget->type = 'checkbox';
        $this->widget->run();
    }

    /**
     * Test that zero type throws InvalidConfigException
     */
    public function testZeroTypeThrowsException()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage("You must define a valid 'type' which must be either 1 (for checkbox) or 2 (for radio).");

        $this->widget->type = 0;
        $this->widget->run();
    }

    /**
     * Test that radio type without items throws InvalidConfigException
     */
    public function testRadioWithoutItemsThrowsException()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage("You must setup the 'items' array for the 'radio' type.");

        $this->widget->type = SwitchInput::RADIO;
        $this->widget->items = [];
        $this->widget->run();
    }

    /**
     * Test that radio type with null items throws InvalidConfigException
     */
    public function testRadioWithNullItemsThrowsException()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage("You must setup the 'items' array for the 'radio' type.");

        $this->widget->type = SwitchInput::RADIO;
        $this->widget->items = null;
        $this->widget->run();
    }

    /**
     * Test that radio type with string items throws InvalidConfigException
     */
    public function testRadioWithStringItemsThrowsException()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage("You must setup the 'items' array for the 'radio' type.");

        $this->widget->type = SwitchInput::RADIO;
        $this->widget->items = "invalid";
        $this->widget->run();
    }

    /**
     * Test that radio type with integer items throws InvalidConfigException
     */
    public function testRadioWithIntegerItemsThrowsException()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage("You must setup the 'items' array for the 'radio' type.");

        $this->widget->type = SwitchInput::RADIO;
        $this->widget->items = 123;
        $this->widget->run();
    }

    /**
     * Test that radio type with object items throws InvalidConfigException
     */
    public function testRadioWithObjectItemsThrowsException()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage("You must setup the 'items' array for the 'radio' type.");

        $this->widget->type = SwitchInput::RADIO;
        $this->widget->items = new \stdClass();
        $this->widget->run();
    }

    /**
     * Test that valid radio configuration doesn't throw exception
     */
    public function testValidRadioConfigurationDoesNotThrowException()
    {
        $this->widget->type = SwitchInput::RADIO;
        $this->widget->items = [
            ['label' => 'Option 1', 'value' => '1'],
            ['label' => 'Option 2', 'value' => '2']
        ];

        // This should not throw any exception
        try {
            $this->widget->run();
            $this->assertTrue(true); // Test passes if no exception is thrown
        } catch (InvalidConfigException $e) {
            $this->fail('Valid radio configuration should not throw exception: ' . $e->getMessage());
        }
    }

    /**
     * Test that valid checkbox configuration doesn't throw exception
     */
    public function testValidCheckboxConfigurationDoesNotThrowException()
    {
        $this->widget->type = SwitchInput::CHECKBOX;

        // This should not throw any exception
        try {
            $this->widget->run();
            $this->assertTrue(true); // Test passes if no exception is thrown
        } catch (InvalidConfigException $e) {
            $this->fail('Valid checkbox configuration should not throw exception: ' . $e->getMessage());
        }
    }

    /**
     * Test edge case: radio with empty array items (should throw exception)
     */
    public function testRadioWithEmptyArrayThrowsException()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage("You must setup the 'items' array for the 'radio' type.");

        $this->widget->type = SwitchInput::RADIO;
        $this->widget->items = []; // Empty array
        $this->widget->run();
    }

    /**
     * Test edge case: checkbox type doesn't require items
     */
    public function testCheckboxWithEmptyItemsDoesNotThrowException()
    {
        $this->widget->type = SwitchInput::CHECKBOX;
        $this->widget->items = []; // Empty array should be fine for checkbox

        try {
            $this->widget->run();
            $this->assertTrue(true); // Test passes if no exception is thrown
        } catch (InvalidConfigException $e) {
            $this->fail('Checkbox with empty items should not throw exception: ' . $e->getMessage());
        }
    }

    /**
     * Test edge case: negative type value
     */
    public function testNegativeTypeThrowsException()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage("You must define a valid 'type' which must be either 1 (for checkbox) or 2 (for radio).");

        $this->widget->type = -1;
        $this->widget->run();
    }

    /**
     * Test edge case: large number type value
     */
    public function testLargeNumberTypeThrowsException()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage("You must define a valid 'type' which must be either 1 (for checkbox) or 2 (for radio).");

        $this->widget->type = 999;
        $this->widget->run();
    }

    /**
     * Test edge case: float type value
     */
    public function testFloatTypeThrowsException()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage("You must define a valid 'type' which must be either 1 (for checkbox) or 2 (for radio).");

        $this->widget->type = 1.5;
        $this->widget->run();
    }

    /**
     * Test edge case: boolean type value
     */
    public function testBooleanTypeThrowsException()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage("You must define a valid 'type' which must be either 1 (for checkbox) or 2 (for radio).");

        $this->widget->type = true;
        $this->widget->run();
    }

    /**
     * Test that radio with array containing non-array items is handled gracefully
     */
    public function testRadioWithMixedItemTypesHandledGracefully()
    {
        $this->widget->type = SwitchInput::RADIO;
        $this->widget->items = [
            ['label' => 'Valid Item', 'value' => '1'],
            'invalid_item', // This should be skipped
            ['label' => 'Another Valid Item', 'value' => '2'],
            123, // This should also be skipped
            null // This should also be skipped
        ];
        $this->widget->name = 'test_radio';
        $this->widget->value = '1';
        $this->widget->containerOptions = ['class' => 'form-group'];
        $this->widget->itemOptions = [];
        $this->widget->labelOptions = [];
        $this->widget->separator = " &nbsp;";
        $this->widget->inlineLabel = true;

        // This should not throw an exception - invalid items should be skipped
        try {
            $this->widget->run();
            $this->assertTrue(true); // Test passes if no exception is thrown
        } catch (\Exception $e) {
            $this->fail('Radio with mixed item types should be handled gracefully: ' . $e->getMessage());
        }
    }

    /**
     * Test tristate with various indeterminate values
     */
    public function testTristateWithCustomIndeterminateValues()
    {
        // Test with integer indeterminate value
        $this->widget->type = SwitchInput::CHECKBOX;
        $this->widget->tristate = true;
        $this->widget->indeterminateValue = 2;
        $this->widget->options = ['id' => 'test-tristate', 'name' => 'test-tristate'];
        $this->widget->containerOptions = ['class' => 'form-group'];

        try {
            $this->widget->run();
            $this->assertTrue(true); // Test passes if no exception is thrown
        } catch (\Exception $e) {
            $this->fail('Tristate with integer indeterminate value should not throw exception: ' . $e->getMessage());
        }

        // Test with string indeterminate value
        $this->widget->indeterminateValue = 'maybe';
        try {
            $this->widget->run();
            $this->assertTrue(true); // Test passes if no exception is thrown
        } catch (\Exception $e) {
            $this->fail('Tristate with string indeterminate value should not throw exception: ' . $e->getMessage());
        }

        // Test with negative indeterminate value
        $this->widget->indeterminateValue = -1;
        try {
            $this->widget->run();
            $this->assertTrue(true); // Test passes if no exception is thrown
        } catch (\Exception $e) {
            $this->fail('Tristate with negative indeterminate value should not throw exception: ' . $e->getMessage());
        }
    }
}
