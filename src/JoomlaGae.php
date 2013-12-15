<?php

namespace JoomlaGae;

use Joomla\Github\Github;
use Joomla\Github\Http;
use Joomla\Github\Package\Data\Blobs;
use Joomla\Github\Package\Data\Trees;
use Joomla\Http\Transport\Stream as StreamTransport;
use Joomla\Registry\Registry;

class JoomlaGae
{
    const GIT_SOURCE = 'https://api.github.com';

    protected $repoOwner = 'joomla';

    protected $branch = 'master';

    protected $tree = null;

    protected $client = null;

    protected $options = null;

    protected $bucket = null;

    public function __construct(Registry $options = null)
    {
        if (!$options)
        {
            $options = new Registry;
            $options->set('api.url', self::GIT_SOURCE);
        }

        $this->options = $options;
        $this->client  = new Http(array(), new StreamTransport($this->options));
    }

    public function setBucket($bucket)
    {
        $this->bucket = $bucket;
    }

    public function setRepoOwner($owner)
    {
        $this->repoOwner = $owner;
    }

    public function setBranch($branch)
    {
        $this->branch = $branch;
    }

    public function process()
    {
        $this->fetchTree();
    }

    public function fetchTree()
    {
        $tree = new Trees($this->options, $this->client);

        // Fetch the tree recursively
        $this->tree = $tree->get($this->repoOwner, 'joomla-cms', $this->branch)->tree;

        return $this->tree;
    }

    public function copyData(array $data)
    {
        if (!isset($data['sha'], $data['path']))
            throw new \Exception('Missing path and SHA');

        if (isset($data['size']))
        {
            // Deal with files
            $blob = new Blobs($this->options, $this->client);
            $file = $blob->get($this->repoOwner, 'joomla-cms', $data['sha']);

            $this->createFile($data['path'], base64_decode($file->content));
        }
        else
        {
            // Deal with folders
            $this->createDir($data['path']);
        }
    }

    protected function createDir($path)
    {
        if (!mkdir('gs://'.$this->bucket.'/'.$path))
            throw new \Exception($php_errormsg);
    }

    protected function createFile($path, $data = '')
    {
        require_once 'Context.php';

        // Get Context options for give extension
        $options = Context::getContextOptions(strrchr($path, '.'));

        $ctx = stream_context_create($options);

        if (!file_put_contents('gs://'.$this->bucket.'/'.$path, $data, 0, $ctx))
            throw new \Exception($php_errormsg);
    }
}
