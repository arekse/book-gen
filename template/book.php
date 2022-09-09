<tr class="wpis" style="page-break-inside:avoid;">
    <td class="lewa" >
        <span class="nazwisko"><?=$wpis['nazwisko']?></span>
        <?if($wpis['ulica']){?>
            <span class="ulica"><?=$wpis['ulica']?></span>
        <?}?>
    </td>
    <td class="prawa" >
        <span class="numer">
            <?if(isset($wpis['kierunkowy'])) {?><?=$wpis['kierunkowy']?><?}?>
            <?if(mb_startsWith($wpis['numer'], [50, 51, 53, 57, 60, 66, 69, 72, 73, 78, 79, 88], false)){?>
                <?$str = preg_replace('#([0-9]{3})([0-9]{3})([0-9]{3})#', '$1-$2-$3', $wpis['numer']);?>
                <?=$str?>
            <?} else {?>
                <?$str = preg_replace('#([0-9]{2})([0-9]{3})([0-9]{2})([0-9]{2})#', '($1)&nbsp;$2-$3-$4', $wpis['numer']);?>
                <?=$str?>
            <?}?>
        </span>
    </td>
</tr>