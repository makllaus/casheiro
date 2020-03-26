
	function openWindow(url,w,h)
	{
		var optionz = "width="+w+",height="+h+",menubar=no,location=no,directories=no,status=no,resizable=yes,scrollbars=yes";
		msgWindow = window.open(url,'WinOpen',optionz);
	}


	var checked = false;
	function checkAll()
	{
		var myform = document.getElementById("form2");
		
		if (checked == false) { checked = true }else{ checked = false }
		for (var i=0; i<myform.elements.length; i++) 
		{
			myform.elements[i].checked = checked;
		}
	}