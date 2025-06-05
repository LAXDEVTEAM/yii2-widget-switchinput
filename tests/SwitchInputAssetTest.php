<?php

namespace kartik\switchinput\tests;

use PHPUnit\Framework\TestCase;
use kartik\switchinput\SwitchInputAsset;

/**
 * Test cases for SwitchInputAsset
 */
class SwitchInputAssetTest extends TestCase
{
    /**
     * @var SwitchInputAsset|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $asset;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a mock asset bundle
        $this->asset = $this->getMockBuilder(SwitchInputAsset::class)
            ->onlyMethods(['setSourcePath', 'setupAssets'])
            ->getMock();
    }

    public function testAssetBundleClass()
    {
        $this->assertInstanceOf('kartik\base\AssetBundle', new SwitchInputAsset());
    }

    public function testInitCallsSetupMethods()
    {
        $this->asset->expects($this->once())
            ->method('setSourcePath')
            ->with($this->stringContains('/assets'));

        $this->asset->expects($this->exactly(2))
            ->method('setupAssets')
            ->withConsecutive(
                ['css', ['css/bootstrap-switch', 'css/bootstrap-switch-kv']],
                ['js', ['js/bootstrap-switch']]
            );

        $this->asset->init();
    }

    public function testSourcePathIsCorrect()
    {
        $reflection = new \ReflectionClass(SwitchInputAsset::class);
        $method = $reflection->getMethod('init');

        // Get the expected source path
        $expectedPath = dirname(__DIR__) . '/assets';

        $this->assertDirectoryExists($expectedPath);
    }

    public function testAssetDependencies()
    {
        $asset = new SwitchInputAsset();

        // Test that the asset bundle has the expected structure
        $this->assertIsArray($asset->css ?? []);
        $this->assertIsArray($asset->js ?? []);
        $this->assertIsArray($asset->depends ?? []);
    }
}
