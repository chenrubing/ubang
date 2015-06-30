<?php if (!defined('THINK_PATH')) exit();?><style>

#max_box{overflow:hidden;width: 100%; margin:10px 0px; height:400px;  position: relative}

.float_search_bar{margin-bottom:10px; position:absolute;left:10px;top:10px; z-index:999999}

.float_search_bar input {width:200px; display:inline-block}

#max_box #map_container{overflow:hidden;height: 400px; position:absolute;left:0px;top:0px; width:100%}

</style>





<div style="overflow:hidden; display:none">

 <input name="<?php if($param["name"] != ''): echo ($param["name"]); else: ?>pos_map<?php endif; ?>" type="hidden" id="T_map" value="<?php echo ($param["value"]); ?>" /></div>





<div id="max_box">

 <div class="float_search_bar">

    <input type="text" id="address" class="form-control" value="泉城广场"/>

    <button type="button" onclick="codeAddress()" class="btn btn-primary" >搜索你的地址</button>

  </div>

<div id="map_container" style="max-width:600px"></div>



</div>

<div class="check-tips" style="margin-top:5px">(查找到您的位置，点击地图进行标注)</div>

<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>

<script type="text/javascript">



    var map = new qq.maps.Map(document.getElementById("map_container"),{
        center: new qq.maps.LatLng(<?php if($param[value]): echo ($param["value"]); else: ?>36.661500,117.021282<?php endif; ?>),
        zoom: 13
    });
	<?php if($param[value]): ?>var marker=new qq.maps.Marker({
            position:new qq.maps.LatLng(<?php echo ($param["value"]); ?>),
            map:map
        });<?php endif; ?>
    qq.maps.event.addListener(map, 'click', function(event) { 
		setResult(event.latLng.getLat(),event.latLng.getLng());	
		var marker=new qq.maps.Marker({
            position:event.latLng, 
            map:map
        });    
		qq.maps.event.addListener(map, 'click', function(event) {
            marker.setMap(null);
  		});         		
    });

	geocoder = new qq.maps.Geocoder({
        complete : function(result){
            map.setCenter(result.detail.location);
            var marker = new qq.maps.Marker({
                map:map,
		     	animation:qq.maps.MarkerAnimation.DROP,
                position: result.detail.location
            });
			//设置Marker自定义图标的属性，size是图标尺寸，该尺寸为显示图标的实际尺寸，origin是切图坐标，该坐标是相对于图片左上角默认为（0,0）的相对像素坐标，anchor是锚点坐标，描述经纬度点对应图标中的位置
        var anchor = new qq.maps.Point(0, 39),
            size = new qq.maps.Size(42, 68),
            origin = new qq.maps.Point(0, 0),
            icon = new qq.maps.MarkerImage(
                "http://open.map.qq.com/doc/img/nilt.png",
                size,
                origin,
                anchor
        );
		marker.setIcon(icon);
 		marker.setShadow(iconb);
        }
    });
    function codeAddress() {
        var address = document.getElementById("address").value;
        geocoder.getLocation(address);
    }
    function setResult(lng, lat){
    	<?php if($param[value]): ?>marker.setMap(null);<?php endif; ?>
    	if(confirm("你确定要选择这个位置了吗？")){
     		$('#T_map').val(lng + "," + lat);
    	}
    }
</script>