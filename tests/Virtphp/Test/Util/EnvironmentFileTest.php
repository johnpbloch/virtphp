<?php

/*
 * This file is part of VirtPHP.
 *
 * (c) Jordan Kasper <github @jakerella>
 *     Ben Ramsey <github @ramsey>
 *     Jacques Woodcock <github @jwoodcock>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Virtphp\Test\Util;

use Virtphp\TestCase;
use Virtphp\TestOutput;
use Virtphp\Util\EnvironmentFile;

/**
 * Filesystem test case
 */
class EnvironmentFileTest extends TestCase
{
    public $expectedEnvPath;

    public $expectedEnvFile;

    public $expectedEnvFolder;

    public $envObj;

    public $output;
    
    public function setup()
    {
        $this->expectedEnvFile = getenv('HOME')
            . DIRECTORY_SEPARATOR
            .  '.virtphp'
            . DIRECTORY_SEPARATOR
            .  'environments.json';
        $this->expectedEnvPath = getenv('HOME')
            . DIRECTORY_SEPARATOR
            . '.virtphp';
        $this->expectedEnvFolder = $this->expectedEnvPath
            . DIRECTORY_SEPARATOR
            . 'envs';

        $this->output = new TestOutput();
        $this->envObj = new EnvironmentFile($this->output);
    }

    /**
     * @covers Virtphp\Util\EnvironmentFile::__construct
     */
    public function testDirname()
    {
        $this->assertEquals($this->envObj->envFile, $this->expectedEnvFile);
        $this->assertEquals($this->envObj->envPath, $this->expectedEnvPath);
        $this->assertEquals($this->envObj->envFolder, $this->expectedEnvFolder);
    }

    /**
     * @covers Virtphp\Util\EnvironmentFile::getFilesystem
     */
    public function testGetFilesystem()
    {
        $this->envObj->getFilesystem();
    }

    /**
     * @covers Virtphp\Util\EnvironmentFile::getEnvironments
     */
    public function testGetEnvironments()
    {
        $filesystemMock = $this->getMock(
            'Virtphp\Test\Mock\FilesystemMock',
            array('getContents')
        );
        $filesystemMock->expects($this->any())
            ->method('getContents')
            ->will($this->returnValue(
                '{"blueEnv":{"path": "/example", "name": "blueEnv"}}'
            ));

        $environment = $this->getMockBuilder('Virtphp\Util\EnvironmentFile')
            ->disableOriginalConstructor()
            ->getMock();
        $environment->expects($this->any())
            ->method('getFilesystem')
            ->will($this->returnValue($filesystemMock));
        $environment->expects($this->any())
            ->method('createEnvironmentsFile')
            ->will($this->returnValue(true));

        $environment->__construct($this->output);

        $this->assertTrue(is_array($environment->envContents));
    }
}
