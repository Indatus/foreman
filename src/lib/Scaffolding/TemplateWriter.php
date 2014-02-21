<?php namespace Scaffolding;

use Illuminate\Filesystem\Filesystem;

/**
 * Class to handle the functions associated with
 * writing out a Foreman template file
 */
class TemplateWriter
{

    /**
     * Default template name
     * 
     * @var string
     */
    protected $default_tpl = 'foreman-tpl.json';

    /**
     * Path to where the template
     * file should be written
     * 
     * @var string
     */
    protected $path;

    /**
     * Filesystem object for moving
     * files around
     * 
     * @var Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;


    /**
     * Constructor
     * 
     * @param string $path
     * @param Illuminate\Filesystem\Filesystem $filesystem
     */
    public function __construct($path, $filesystem)
    {
        $this->path = $path;
        $this->filesystem = $filesystem;
    }


    /**
     * Getter to return the path
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }


    /**
     * Function to get the structure / keys and default
     * values we should use for the template
     * 
     * @return array
     */
    public function getStructureArray()
    {
        return [
            'structure' => [
                'copy' => [
                    ['from' => '', 'to' => '']
                ],
                'move' => [
                    ['from' => '', 'to' => '']
                ],
                'delete' => [],
                'touch' => [],
                'mkdir' => [],
            ],
            'composer' => [
                'require' => [
                    ['package' => 'laravel/framework', 'version' => '4.1.*']
                ],
                'require-dev' => [
                    ['package' => '', 'version' => '']
                ],
                'autoload' => [
                    'classmap' => [
                        'app/commands',
                        'app/controllers',
                        'app/models',
                        'app/database/migrations',
                        'app/database/seeds',
                        'app/test/TestCase.php'
                    ],
                    'psr-0' => [],
                    'psr-4' => []
                ]
            ]
        ];
    }


    /**
     * Function to return a json encoded string
     * of the structure and default values
     * 
     * @return string
     */
    public function getStructureJson()
    {
        return json_encode($this->getStructureArray(), JSON_PRETTY_PRINT);
    }


    /**
     * Function to write out the template to
     * the given file if it is indeed a file, and as
     * the default template name at the given path
     * if it is a directory.
     * 
     * @return string the file that was witten
     */
    public function write()
    {

        if ($this->filesystem->isDirectory($this->path)) {

            $file = implode(DIRECTORY_SEPARATOR, [$this->path, $this->default_tpl]);

        } else {

            $file = $this->path;
        }

        $this->filesystem->put($file, $this->getStructureJson());

        return $file;
    }
}
