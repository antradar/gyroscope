showwideviewdemo=function(){}

tabscrollfunc_wide=function(wideid){return function(){
		var marks=[];		
		for (var i=0;i<15;i++) marks.push('section'+i);
		synclbookmarks('wide',wideid,marks);
}};



