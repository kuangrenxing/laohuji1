var starArr = new Array(12);
starArr[1] = "双鱼座";
starArr[2] = "水瓶座";
starArr[3] = "魔羯座";
starArr[4] = "射手座";
starArr[5] = "天蝎座";
starArr[6] = "天秤座";
starArr[7] = "处女座";
starArr[8] = "狮子座";
starArr[9] = "巨蟹座";
starArr[10] = "双子座";
starArr[11] = "金牛座";
starArr[12] = "白羊座";

function checkName(){
	if($("#pname").val()=="" || $("#pname").val().length==0){
		alert("名字不能为空");
		$("#pname").focus();
		return false;
	}else
		return true;
}

function timeout(){
	
	
	setTimeout(function(){
		$("#play").css("background","url(http://www.tuolar.com/apps/sinala/src/img/star.png)");
		$("#add").stopTime();
		var pc = $("#pcontent").val();
		$("#pyu").text(pc);
		$("#img1").attr('src',$("#prodpic1").val());
		$("#img2").attr('src',$("#prodpic2").val());
		$("#img3").attr('src',$("#prodpic3").val());
		$("#img4").attr('src',$("#prodpic4").val());
		$("#px").attr('src',$("#pximg").val());
		
		$("#pxForm").submit();
	}, 10000);
	$("#J_flag").val("0");
	
}



$(document).ready(function() {
	$("#pname").blur(function(){
		if($("#pname").val()!="" || $("#pname").val().length!=0)
		{
			$("#play").trigger("click");
		}
	});
	
	$("#pname").focus();
	
	$("#play").click(function(){
	if($("#J_flag").val()=='0'){//当前是暂停状态下
		
		if(checkName()==false){			
		}else{			
		
		$(this).css("background","url(http://www.tuolar.com/apps/sinala/src/img/stop.png)");
		var ids=[],px=[],itema=[],itemb=[],itemc=[],itemd=[],yyu=[],pyu=[],pxid=[],itemida=[],itemidb=[],itemidc=[],itemidd=[];
		var tl_len = 0,tl_k = 0;
		var star = $("#star").val();
		var name = $("#pname").val();
		$.ajax({
			url :"http://laohuji.dev/?m=ajax",
			type:"POST",
			data:"name="+name+"&star="+star,
			success:function(xml){
				$(xml).find("app").each(function(){ids.push($(this).attr("id"));tl_len ++;});
				$(xml).find("px").each(function(){px.push($(this).attr("img"));pxid.push($(this).attr("id"));});
				$(xml).find("itema").each(function(){itema.push($(this).attr("img"));itemida.push($(this).attr("id"));});
				$(xml).find("itemb").each(function(){itemb.push($(this).attr("img"));itemidb.push($(this).attr("id"));});
				$(xml).find("itemc").each(function(){itemc.push($(this).attr("img"));itemidc.push($(this).attr("id"));});
				$(xml).find("itemd").each(function(){itemd.push($(this).attr("img"));itemidd.push($(this).attr("id"));});
				$(xml).find("yyu").each(function(){yyu.push($(this).attr("name"));});
				$(xml).find("pyu").each(function(){pyu.push($(this).attr("name"));});
			}
		});

		var pxLink = "http://www.tuolar.com/px/detail.php?id=";
		var prodLink = "http://www.tuolar.com/prod/detail.php?id=";
		$("#add").everyTime('75ms',function(){
			var rand = parseInt(10*Math.random());
			$("#img1").attr('src',preload_proda[rand].src); /* $("#url1").attr('href',prodLink+itemida[tl_k]); */
			$("#img2").attr('src',preload_prodb[rand].src);/* $("#url2").attr('href',prodLink+itemidb[tl_k]); */
			$("#img3").attr('src',preload_prodc[rand].src);/* $("#url3").attr('href',prodLink+itemidc[tl_k]); */
			$("#img4").attr('src',preload_prodd[rand].src);/* $("#url4").attr('href',prodLink+itemidd[tl_k]); */
			$("#px").attr('src',preload_pximg[rand].src);/* $("#pxurl").attr('href',pxLink+pxid[tl_k]); */
			$("#prodpic1").val(itema[tl_k]);$("#prodpic2").val(itemb[tl_k]);$("#prodpic3").val(itemc[tl_k]);$("#prodpic4").val(itemd[tl_k]);
			$("#pximg").val(px[tl_k]);$("#appid").val(ids[tl_k]);$("#pcontent").val(pyu[tl_k]);$("#ynum").text(yyu[tl_k]);
			tl_k ++;
			if(tl_k > tl_len) tl_k = 0;
		});
		setTimeout(timeout(),1000);
		
	}
	
	}else{
		
			if(checkName()==false){
				
			}else{
				timeout();
			}
		
	
	}
	});
	
	
	$("#myForm dl dd").each(function(e){
		$(this).css("top",(e+1)*28);
		$(this).hover(function(){
			$(this).css("background","#FFF");
		},function(){
			$(this).css("background","#EBC6D9");
		})
	});
	$("#myForm dd").click(function(){
		var ddtext=$(this).text();
		var dttext=$(".citySelect dl dt span").text();
		$("#myForm dl dt span").text(ddtext);
		for(var tl_i = 1 ; tl_i < 13 ; tl_i++){
			if(starArr[tl_i] == ddtext){
				var tl_key = tl_i;
				break;
			}
		}
		$("#star").val(tl_key);
		$("#myForm dl dd").hide();
	});
	$("#myForm dl i").click(function(){
		$("#myForm dl dd").toggle();
	});
});