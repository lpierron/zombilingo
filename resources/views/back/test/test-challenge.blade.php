<?php
switch ("daily") {
    case "daily":
        $now = "1 day";
        $previous_period = "2 day";
        $type = "quotidien";
        $duration="";
        break;
    case "weekly":
        $now = "1 day";
        $previous_period = "8 day";
        $type = "hebdomadaire";
        $duration=" en une semaine";
        break;
    case "monthly":
        $now = "1 day";
        $previous_period = "1 month";
        $type = "mensuel";
        $duration=" en un mois";
        break;
}

$dates_challenge = array('min'=>'2016-01-27 01:00:00','max'=>'2016-02-03 01:00:00');
$dates_challenge_force = array('min'=>'2016-01-10 00:00:00','max'=>'2016-01-26 23:59:59');
?>
        <table style="width:100%;padding-bottom:20px" border="0" cellpadding="0" cellspacing="0">
            <tr><th style="text-align:left;">Position</th><th style="text-align:left">Pseudo</th><th>Evolution</th><th style="text-align:right">Points</th></tr>
        @if($scores_annotation_user['challenge']) 
            @foreach($neighbors_annotations['challenge']['sup'] as $neighbor)
                <?php
                    $score = $scores_annotation->getByUserAndPeriode($neighbor->user_id,null,$now,12);
                    $previous_score = $scores_annotation->getByUserAndPeriode($neighbor->user_id,null,$previous_period,12);
                    if($score && $previous_score){
                        $diff_rank =  Html::formatRank($previous_score->rank - $score->rank);
                    }
                    else 
                        $diff_rank = '-';
                ?>        
                <tr><td>{{ $previous_score->rank }}</td><td>{{ $score->rank }}</td></tr>
                <tr class="rank rank-points"><td>{{ $neighbor->rank }}</td><td>{{ $neighbor->username }}</td><td style="text-align:center">({!! $diff_rank !!})</td><td style="text-align:right">{{ Html::formatScore($neighbor->score) }}</td></tr>
            @endforeach
                <?php
                    $score = $scores_annotation->getByUserAndPeriode($user,null,$now,12);
                    $previous_score = $scores_annotation->getByUserAndPeriode($user,null,$previous_period,12);
                    if($score && $previous_score){
                        $diff_rank =  Html::formatRank($previous_score->rank - $score->rank);
                    }
                    else 
                        $diff_rank = '-';
                ?>
     
                <tr class="rank rank-points self" style="background-color:#CEF6CE;"><td>{{ $scores_annotation_user['challenge']->rank }}</td><td>{{ $scores_annotation_user['challenge']->username }}</td><td style="text-align:center">({!! $diff_rank !!})</td><td style="text-align:right">{{ Html::formatScore($scores_annotation_user['challenge']->score) }}</td></tr>
            
                @foreach($neighbors_annotations['challenge']['inf'] as $neighbor)
                    <?php
                        $score = $scores_annotation->getByUserAndPeriode($neighbor->user_id,null,$now,12);
                        $previous_score = $scores_annotation->getByUserAndPeriode($neighbor->user_id,null,$previous_period,12);
                        if($score && $previous_score){
                            $diff_rank =  Html::formatRank($previous_score->rank - $score->rank);
                        }
                        else 
                            $diff_rank = '-';
                    ?>            
                    <tr class="rank rank-points"><td>{{ $neighbor->rank }}</td><td>{{ $neighbor->username }}</td><td style="text-align:center">({!! $diff_rank !!})</td><td style="text-align:right">{{ Html::formatScore($neighbor->score) }}</td></tr>
                @endforeach
        @endif
        </table>
