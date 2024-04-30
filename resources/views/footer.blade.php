<style>
    footer {
        position: fixed;
        bottom: 0;
        width: 100%;
        height: 40px;
        line-height: 40px;
        text-align: center;
        font-size: 12px;
        color: #999;
    }
    footer #fps {
        position: absolute;
        left: 0;
        bottom: 0;
        width: 40px;
        height: 40px;
        line-height: 40px;
        text-align: center;
        font-size: 12px;
        color: #00ffff;
        border-right: 1px solid #999;
    }
    footer #benchcont {
        position: absolute;
        left: 40px;
        bottom: 0;
        width: 140px;
        height: 40px;
        line-height: 40px;
        text-align: center;
        font-size: 12px;
        border-right: 1px solid #999;
    }
    footer #bench {
        color: #00ffff;
    }
</style>
<footer>
<span id="fps"></span>
<span id="benchcont">[<span id="bench">benchmarking...</span>]</span>
<span class="rainglow">requiem.moe version <?php echo $currentVer ?> © 2017 - <?php $currentYear = date("Y"); echo $currentYear; ?> SevensRequiem</span>
<?php
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
?>
<span class='' id='helloip'>hello「︎{{$ip_address}}」︎!!</span>
</footer>