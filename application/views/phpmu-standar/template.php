<!DOCTYPE HTML>
<html lang = "id">
<head>
<title><?php echo $title; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="robots" content="index, follow">
	<meta name="description" content="<?php echo $description; ?>">
	<meta name="keywords" content="<?php echo $keywords; ?>">
	<meta name="author" content="mycoding.id">
	<meta name="robots" content="all,index,follow">
	<meta http-equiv="Content-Language" content="id-ID">
	<meta NAME="Distribution" CONTENT="Global">
	<meta NAME="Rating" CONTENT="General">
	<link rel="canonical" href="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>"/>
	
	<?php 
		if($this->uri->segment(1)=='agenda' && $this->uri->segment(2) == 'detail') {
			$rows = $this->model_utama->view_where('agenda',array('tema_seo' => $this->uri->segment(3)))->row_array();
			$gambar_url = 'asset/foto_agenda/'.$rows['gambar'];			
			$content_url = 'agenda/detail/'. $rows['tema_seo'];
		} else {
			$rows = $this->model_utama->view_where('berita',array('judul_seo' => $this->uri->segment(1)))->row_array();
			$gambar_url = 'asset/foto_berita/'.$rows['gambar'];
			$content_url = $this->uri->segment(1);
		}

		if( !empty($rows) ) {
			?>
			<meta property="og:title" content="<?php echo $title;?>" />
			<meta property="og:type" content="article" />
			<meta property="og:url" content="<?php echo base_url($content_url);?>" />
			<meta property="og:image" content="<?php echo base_url($gambar_url);?>" />
			<meta property="og:description" content="<?php echo $description;?>"/>
		<?php
		} 
	?>
	
	<link rel="shortcut icon" href="<?php echo base_url(); ?>asset/images/<?php echo favicon(); ?>" />
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="rss.xml" />
	<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>template/<?php echo template(); ?>/background/<?php echo background(); ?>/reset.css" />
	<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>template/<?php echo template(); ?>/background/<?php echo background(); ?>/main-stylesheet.css" />
	<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>template/<?php echo template(); ?>/background/<?php echo background(); ?>/shortcode.css" />
	<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>template/<?php echo template(); ?>/background/<?php echo background(); ?>/fonts.css" />
	<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>template/<?php echo template(); ?>/background/<?php echo background(); ?>/responsive.css" />
	<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>template/<?php echo template(); ?>/background/style.css">
	<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>template/<?php echo template(); ?>/background/ideaboxWeather.css">
	<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>template/<?php echo template(); ?>/slide/slide.css">

	<link rel="stylesheet" href="<?php echo base_url(); ?>template/<?php echo template(); ?>/lightbox/lightbox.css">
	<script type="text/javascript" src="<?php echo base_url(); ?>template/<?php echo template(); ?>/jscript/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>template/<?php echo template(); ?>/jscript/jquery-latest.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>template/<?php echo template(); ?>/jscript/theme-scripts.js"></script>
	<?php if ($this->uri->segment(1)=='main' OR $this->uri->segment(1)==''){ ?>
	<script type="text/javascript" src="<?php echo base_url(); ?>template/<?php echo template(); ?>/slide/js/jssor.slider-23.1.0.mini.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>template/<?php echo template(); ?>/slide/js/slide.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>template/<?php echo template(); ?>/slide/js/slide_baznas.js"></script>
	<?php } ?>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.0";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
	</script>
	<style type="text/css">.the-menu a.active{ /*color:#ea8400 !important;*/ } </style>
</head>
<body>
<div id='Back-to-top'>
  <img alt='Scroll to top' src='<?php echo base_url(); ?>asset/css/img/top.png'/>
</div>
<div class="boxed active">	
	<div class="header">
		<?php include "header.php"; ?>
	</div>
	
	<div class="content">
		<div class="wrapper">	
			<?php 
			if ($this->uri->segment(1)=='main' OR $this->uri->segment(1)==''){
				$slider = $this->model_utama->view_ordering_limit('slider','urutan','ASC',0,10);
				if ($slider->num_rows() > 0){
			?>
				<div id="jssor_baznas" style="position:relative;margin:0 auto;top:0px;left:0px;width:2880px;height:570px;overflow:hidden;visibility:hidden;">
					<!-- Loading Screen -->
					<div data-u="loading" style="position:absolute;top:0px;left:0px;background-color:rgba(0,0,0,0.7);">
						<div style="filter: alpha(opacity=70); opacity: 0.7; position: absolute; display: block; top: 0px; left: 0px; width: 100%; height: 100%;"></div>
						<div style="position:absolute;display:block;background:url('<?php echo base_url(); ?>template/<?php echo template(); ?>/slide/img/loading.gif') no-repeat center center;top:0px;left:0px;width:100%;height:100%;"></div>
					</div>
					<div data-u="slides" style="cursor:default;position:relative;top:0px;left:0px;width:2880px;height:570px;overflow:hidden;">
						<?php
						foreach ($slider->result_array() as $sl) {
							echo "<div>
									<a href='$sl[url]'><img data-u='image' src='".base_url()."asset/foto_slider/$sl[gambar]' /></a>
								  </div>";
						}
						?>
					</div>
					<!-- Bullet Navigator -->
					<div data-u="navigator" class="jssorb05" style="bottom:16px;right:16px;" data-autocenter="1">
						<!-- bullet navigator item prototype -->
						<div data-u="prototype" style="width:16px;height:16px;"></div>
					</div>
					<!-- Arrow Navigator -->
					<span data-u="arrowleft" class="jssora22l" style="top:0px;left:8px;width:40px;height:58px;" data-autocenter="2"></span>
					<span data-u="arrowright" class="jssora22r" style="top:0px;right:8px;width:40px;height:58px;" data-autocenter="2"></span>
				</div>
			<?php 
				}
			} 
			?>

			<center>
			<?php
			if ($this->uri->segment(1)=='main' OR $this->uri->segment(1)==''){
			  $iklanatas = $this->model_utama->view('iklanatas');
			  foreach ($iklanatas->result_array() as $b) {
				$string = $b['gambar'];
				if ($b['gambar'] != ''){
					if(preg_match("/swf\z/i", $string)) {
						echo "<embed width='100%' src='".base_url()."asset/foto_iklanatas/$b[gambar]' quality='high' type='application/x-shockwave-flash'>";
					} else {
						echo "<a href='$b[url]' target='_blank'><img style='margin-bottom:5px' width='100%' src='".base_url()."asset/foto_iklanatas/$b[gambar]' alt='$b[judul]' /></a>";
					}
				}
				if (trim($b['source']) != ''){ echo "$b[source]"; }
			  }
			}
			?>
			</center>

			<?php 
			if ($this->uri->segment(1)=='kategori'){
				$bb = $this->db->query("SELECT * FROM kategori where kategori_seo='".cetak($this->uri->segment(3))."'")->row_array();
				if ($bb['gambar_utama'] != ''){
					if(preg_match("/swf\z/i", $string)) {
						echo "<embed width='100%' src='".base_url()."asset/foto_berita/$bb[gambar_utama]' quality='high' type='application/x-shockwave-flash'>";
					} else {
						echo "<a href='#' target='_blank'><img style='margin-bottom:5px' width='100%' src='".base_url()."asset/foto_berita/$bb[gambar_utama]' alt='$bb[nama_kategori]' /></a>";
					}
				}
			}
			?>

			<div class="main-content">
				<?php echo $contents; ?>
			<div class="clear-float"></div>
			</div>
		</div>
	</div>

	<footer>
		<div class="footer">
			<?php 
				include "footer.php";
				$this->model_utama->kunjungan(); 
			?>
		</div>
	</footer>
</div>
	<!-- Scripts -->
	<script type='text/javascript'>
	$(function() { $(window).scroll(function() {
	    if($(this).scrollTop()>400) { $('#Back-to-top').fadeIn(); }else { $('#Back-to-top').fadeOut();}});
	    $('#Back-to-top').click(function() {
	        $('body,html')
	        .animate({scrollTop:0},300)
	        .animate({scrollTop:40},200)
	        .animate({scrollTop:0},130)
	        .animate({scrollTop:15},100)
	        .animate({scrollTop:0},70);
	        });
	});

	function jam(){
		var waktu = new Date();
		var jam = waktu.getHours();
		var menit = waktu.getMinutes();
		var detik = waktu.getSeconds();
		 
		if (jam < 10){ jam = "0" + jam; }
		if (menit < 10){ menit = "0" + menit; }
		if (detik < 10){ detik = "0" + detik; }
		var jam_div = document.getElementById('jam');
		jam_div.innerHTML = jam + ":" + menit + ":" + detik;
		setTimeout("jam()", 1000);
	} jam();

	</script>

<script type="text/javascript">
  (function (jQuery) {
  $.fn.ideaboxWeather = function (settings) {
  var defaults = {
  modulid   :'Swarakalibata',
  width :'100%',
  themecolor    :'#2582bd',
  todaytext :'Hari Ini',
  radius    :true,
  location  :' Jakarta',
  daycount  :7,
  imgpath   :'img_cuaca/', 
  template  :'vertical',
  lang  :'id',
  metric    :'C', 
  days  :["Minggu","Senin","Selasa","Rabu","Kamis","Jum'at","Sabtu"],
  dayssmall :["Mg","Sn","Sl","Rb","Km","Jm","Sa"]};
  var settings = $.extend(defaults, settings);

  return this.each(function () {
  settings.modulid = "#" + $(this).attr("id");
  $(settings.modulid).css({"width":settings.width,"background":settings.themecolor});

  if (settings.radius)
  $(settings.modulid).addClass("ow-border");

  getWeather();
  resizeEvent();

  $(window).on("resize",function(){
  resizeEvent();});

  function resizeEvent(){
  var mW=$(settings.modulid).width();

  if (mW<200){
  $(settings.modulid).addClass("ow-small");}
  else{
  $(settings.modulid).removeClass("ow-small");}}

  function getWeather(){$.get("http://api.openweathermap.org/data/2.5/forecast/daily?q="+settings.location+"&mode=xml&units=metric&cnt="+settings.daycount+"&lang="+settings.lang+"&appid=b318ee3082fcae85097e680e36b9c749", function(data) {
  var $XML = $(data);
  var sstr = "";
  var location = $XML.find("name").text();
  $XML.find("time").each(function(index,element) {
  var $this = $(this);
  var d = new Date($(this).attr("day"));
  var n = d.getDay();
  var metrics = "";
  if (settings.metric=="F"){
  metrics = Math.round($this.find("temperature").attr("day") * 1.8 + 32)+"¡ÆF";}
  else{
  metrics = Math.round($this.find("temperature").attr("day"))+"¡ÆC";}

  if (index==0){
  if (settings.template=="vertical"){
  sstr=sstr+'<div class="ow-today">'+
  '<span><img src="<?php echo base_url(); ?>asset/'+settings.imgpath+$this.find("symbol").attr("var")+'.png"/></span>'+
  '<h2>'+metrics+'<span>'+ucFirst($this.find("symbol").attr("name"))+'</span><b>'+location+' - '+settings.todaytext+'</b></h2>'+
  '</div>';}
  else{
  sstr=sstr+'<div class="ow-today">'+
  '<span><img src="<?php echo base_url(); ?>asset/'+settings.imgpath+$this.find("symbol").attr("var")+'.png"/></span>'+
  '<h2>'+metrics+'<span>'+ucFirst($this.find("symbol").attr("name"))+'</span><b>'+location+' - '+settings.todaytext+'</b></h2>'+
  '</div>';}}
  else{
  if (settings.template=="vertical"){
  sstr=sstr+'<div class="ow-days">'+
  '<span>'+settings.days[n]+'</span>'+
  '<p><img src="<?php echo base_url(); ?>asset/'+settings.imgpath+$this.find("symbol").attr("var")+'.png" title="'+ucFirst($this.find("symbol").attr("name"))+'"> <b>'+metrics+'</b></p>'+
  '</div>';}
  else{
  sstr=sstr+'<div class="ow-dayssmall" style="width:'+100/(settings.daycount-1)+'%">'+
  '<span title='+settings.days[n]+'>'+settings.dayssmall[n]+'</span>'+
  '<p><img src="<?php echo base_url(); ?>asset/'+settings.imgpath+$this.find("symbol").attr("var")+'.png" title="'+ucFirst($this.find("symbol").attr("name"))+'"></p>'+
  '<b>'+metrics+'</b>'+
  '</div>';}}});

  $(settings.modulid).html(sstr); 
  });}

  function ucFirst(string) {
  return string.substring(0, 1).toUpperCase() + string.substring(1).toLowerCase();}});
  };
  })(jQuery);

  $(document).ready(function(){
  $('#example1').ideaboxWeather({
  location      :' Jakarta, ID'});});
</script>

<script>
$(function(){
    var url = window.location.pathname, 
        urlRegExp = new RegExp(url.replace(/\/$/,'') + "$"); // create regexp to match current url pathname and remove trailing slash if present as it could collide with the link in navigation in case trailing slash wasn't present there
        // now grab every link from the navigation
        $('.the-menu a').each(function(){
            // and test its normalized href against the url pathname regexp
            if(urlRegExp.test(this.href.replace(/\/$/,''))){
                $(this).addClass('active');
            }
        });

});
</script>
</body>
</html>