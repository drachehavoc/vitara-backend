<?php 

use \Helper\Relational\Map;

return function () 
{
    // --------------------------------------------------------------------------
    // --- CONDITION EXAMPLE
    // --------------------------------------------------------------------------
    
    // $cond = Map\Cond::
    //     where('anchor', '='       , Map\ANCHOR, 'ancora1')
    //     ->and('anchor', 'in'      , Map\ANCHOR, 'ancora2')
    //     ->and('anchor', 'between' , Map\ANCHOR, 'ancora3')
    //     ->or ('normal', '='       , 'XXX')
    //     ->or ('normal', 'in'      , 1, 2, 3, 4, 5, 6)
    //     ->or ('normal', 'between' , 1, 2);
    
    // $cond->setAnchors([
        //     'ancora1' => '__=1',
        //     'ancora2' => ["_in1", "_in2", "_in3", "_in4", "_in5"],
        //     'ancora3' => ["_bt1", "_bt2"],
        // ]);
        
    // print_r( $cond->getPDOParams() ); 
        
    // --------------------------------------------------------------------------
    // --- EXAMPLE SELECT AND SUB SELECT
    // --------------------------------------------------------------------------
    
    $telefones = (new Map("telefones"))
        ->select
        ->condition($condTel = Map\Cond('pessoa', '=', Map\ANCHOR, 'telefones_anc'));
        
    $functionSelectTelefones = function ($row) use ($telefones) 
    {
        $telefones->condition->setAnchors($row);
        return $telefones->fetch();
    };
        
    $functionSelectTelefonesAlternative = function ($row) use ($telefones, $condTel) 
    {
        $condTel->setAnchors($row);
        return $telefones->fetch();
    };

    return (new Map("pessoas"))
        ->select
        ->columns('id', 'nome' , 'nascimento', ['id' => 'telefones_anc'])
        ->customColumn('telefones_sel', $telefones)
        ->customColumn('telefones_fun', $functionSelectTelefones)
        ->customColumn('telefones_alt', $functionSelectTelefonesAlternative)
        ->customColumn('telefones_anc', $telefones) // replace column telefones_anc
        ->condition(Map\Cond('id', '=', 1))
        ->fetch();
};