<?php

class Gate
{
    protected static $instance = null;

    protected $config = null;

    static function getInstance()
    {
        return self::$instance ?? self::$instance = new self();
    }


    protected function __construct()
    {
        $this->loadConfig();
        $this->enter();
    }

    protected function loadConfig()
    {
        $config = $this->config = Config::getInstance();
        $gatesFile = $config->get(null, 'gates');
        $config->set(['paths' => []], 'gates');
        try {
            $config->load($gatesFile, 'gates');
        } catch (\Exception $e) {
            throw new \Exception("Não foi possível encontrar o arquivo de gates em `{$gatesFile}`, verifique se a chave `gates` no arquivo de configuração base aponta para um arquivo existente e se este retorna um array");
        }
    }

    protected function startWalk($target)
    {
        if (is_array($target)) {
            foreach($target as $currTarget)
                $this->startWalk($currTarget);
        }

        if ($target instanceof \Closure) {
            ResponseHandler
                ::getInstance()
                ->addResponse($target());
        }

        if (is_string($target)) {
            $appsFolder = $this->config->get(null, 'applications');
            $appsFileConf = $appsFolder . $target;

            if (!file_exists($appsFileConf))
                throw new \Exception("O arquivo de configuração de aplicação `{$appsFileConf}` não foi encontrado, verifique se a chave [paths][{$host}] do arquivo de gates `" . $this->config->get(null, 'gates') . "` aponta para um arquivo real, caso este não seja o erro verifique os arquivos `" . implode(',', $this->config->getLoadedFiles()) . "` possuem a chave [applications], caso possuam verifique se elas aponta para uma pasta real do sistema");

            $function = require $appsFileConf;

            if (!($function instanceof \Closure))
                throw new \Exception("O arquivo de de aplicação `{$appsFileConf}` precisa ser do tipo function, porém foi informado um arquivo do tipo `" . gettype($function) . "`");

            ResponseHandler
                ::getInstance()
                ->addResponse($function());
        }
    }

    protected function enter()
    {
        $gates = (Object)$this->config->get('gates');
        $host = $this->config->get(null, 'host');

        if (!isset($gates->paths[$host]))
            throw new \Exception("Não foi encontrada um path para o host `{$host}`, no arquivo de gates `" . $this->config->get(null, 'gates') . "`, é necessário criar uma chave [paths][{$host}], contendo o caminho relativo para uma pasta que contenha o arquivo de configuração de aplicação `config.php`, este deve retornar um array que comfigure a aplicação");

        $target = $gates->paths[$host];

        if (!is_string($target)
            && !is_array($target)
            && !($target instanceof \Closure))
            throw new \Exception("A chave [paths][{$host}], definida em `" . $this->config->get(null, 'gates') . "`, precisa ser do tipo string, array ou function, porem foi informado um valor do tipo `" . gettype($target) . "`");

        $this->startWalk($target);
    }
}