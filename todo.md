```
📂Helper\Relational
├─📄Map
│ ╰─💡padronizar métodos Map\[Save|Select|Delete]::callback() criando uma espécie de sub-helpers dos calbacks
├─📄Map\Select
│ ├─️🔥✅2018-10-21, refazer a classe para aceitar o novo modelo (driver)
│ ╰─⚙️callback()
│   ╰─🔨criar uma método estilo a Map\Save::callback() (para execuções em massa e não para impressão, como é a customColumn())
├─📄Map\Delete
│ ╰─🔥✅2018-10-22, criar
├─📄Map\Save
│ ╰─⚙️callback()
│   ├─🔨permitir recebimento de objetos do tipo Map\Select 
│   ╰─🔨permitir recebimento de objetos do tipo Map\Delete 
╰─📄Drive\mysql
  ├─️⚙️save()
  │ ╰─💡depois de executar/inserir, caso não tenha `condition` cria um apartir do que foi inserido para que no próximo save seja feito update e não insert
  ╰─⚙️columnExists()
    ╰─🔨✅2018-10-21, permitir adicionar alias para colunas

✅pronto
💡ideia
🔧concertar
🔥concertar/urgente
🔨criar
💊remover gambiarra
⚙️Method
📂namespace
📄Classe/Arquivo
📃⭕⭐⚡🚨🏃⭐🌟✨🤞💀🏁
```