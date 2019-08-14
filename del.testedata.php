<?php

        $dataHoje = new DateTime();	
		$dataUpdateTable = '2019-08-04 00:00:00';
		$dataUpdateRel = new DateTime($dataUpdateTable);
        $diffDatas = $dataHoje->diff($dataUpdateRel);
        
        echo $diffDatas->i;