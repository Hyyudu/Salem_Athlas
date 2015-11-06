<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Хыиуду
 * Date: 31.05.15
 * Time: 4:38
 * To change this template use File | Settings | File Templates.
 */
header('Content-type: text/html; charset=utf-8');
include_once('../templates/athlas_output_start.htm');
include_once('../templates/output_end.htm');

?>
<script type="text/javascript">
music = '';

function rotate(prefix, cnt)   {
    $('.image_holder').attr('id', 'img_carousel');
    arr = ['adept', 'andromacha', 'attack', 'awake']
    i=0;
    document.rotId = setInterval(function(){
        $('#img_carousel').css({backgroundImage: 'url(../images/athlas/'+prefix+i+'.jpg)'});
        i = (i+1)%cnt;
    }, 3000)

}

function transfer(page) {
	clearInterval(document.intId)
	clearInterval(document.rotId)
    if (page == undefined)
        page = '';
    $('#main').animate({opacity: 0}, 1000, 'linear', function() {
        jQuery.ajax({
            url: '../athlas_process.php',
            method: 'POST',
            data: {action: 'athlas',
                page: page
            },
            success: function(data) {
                eval('data='+data)
                console.log(data)
                if (data.hypnomap)  {
                    document.location.href='../hypnomap.php';
                    return
                }
                if (data.music != music && data.music != '' && data.music != null && data.music != undefined)    {
                    music_arr = data.music.split('#')
                    if (music_arr[1] == undefined)
                        music_arr[1] = 0;
                    console.log(music_arr)
                    $('#audio').attr('src', '../music/'+music_arr[0])
                    $('#audio').currentTime = music_arr[1]
                    $('#audio').attr('volume', 0);
                    $('#audio')[0].play();
                    $('#audio').animate({volume: 1}, 1000, 'linear')

                    music = music_arr[1]
                }
                $('#main').html(data.text).animate({opacity: 1},  1000, 'linear');
                $('.image_holder').style = {mask: "url('#mymask')"};
				$(window).scrollTop(0)
            }
        })
    })

}

$(document).ready(function(){
    transfer()
});
</script>
