/*
Cree Keyboard
(c) Schien Dong, 2019 Antradar Software Inc.
*/

if (gid('imecree')) gid('imecree').style.display='inline';

creeime=function(d){
	if (!d) d=document.hotspot;
	if (!d) return;
	if (!d.cree){
		d.style.border='dashed 1px #aaaaff';
		d.cree=true;
		
		if (!d.oldkeydown) d.oldkeydown=d.onkeydown;
		if (!d.oldkeyup) d.oldkeyup=d.onkeyup;

		d.onkeydown=function(e){
			var charcode;
			if (e) charcode=e.which; else charcode=event.charCode;
						
			if (charcode==16) d.upperkey=true;
								
			var charmap={
				'ks_49':'ᐞ', //1
				'ks_50':'ᑊ', //2
				'ks_51':'ᐟ', //3
				'ks_52':'ᐠ', //4
				'ks_53':'ᐨ', //5
				'ks_54':'ᑦ', //6
				'ks_55':'ᐣ', //7
				'ks_56':'ᐢ', //8
				'ks_57':'ᐩ', //9
				'ks_173':'ᕒ', //-
				'ks_61':'ᐤ', //=
				
				'ks_81':'ᐂ', //Q
				'ks_87':'ᐰ', //W
				'ks_69':'ᑍ', //E
				'ks_82':'ᑬ', //R
				'ks_84':'ᒊ', //T
				'ks_89':'ᒤ', //Y
				'ks_85':'ᓃ', //U
				'ks_73':'ᓯ', //I
				'ks_79':'ᔨ', //O
				'ks_219':'ᐦ', //{
				'ks_221':'?', //}
				
				'ks_65':'ᐆ', //A
				'ks_83':'ᐴ', //S
				'ks_68':'ᑑ', //D
				'ks_70':'ᑰ', //F
				'ks_71':'ᒎ', //G
				'ks_72':'ᒨ', //H
				'ks_74':'ᓅ', //J
				'ks_75':'ᓰ', //K
				'ks_76':'ᔫ', //L
				'ks_90':'ᐋ', //Z
				'ks_88':'ᐹ', //X
				'ks_67':'ᑖ', //C
				'ks_86':'ᑳ', //V
				'ks_66':'ᒑ', //B
				'ks_78':'ᒫ', //N
				'ks_77':'ᓈ', //M
				'ks_188':'ᓵ', //<
				'ks_190':'ᔮ', //>
				
				
				'k_49':'ᐁ', //1
				'k_50':'ᐯ', //2
				'k_51':'ᑌ', //3
				'k_52':'ᑫ', //4
				'k_53':'ᒉ', //5
				'k_54':'ᒣ', //6
				'k_55':'ᓀ', //7
				'k_56':'ᓭ', //8
				'k_57':'ᔦ', //9
				'k_173':'ᓬ', //-
				'k_61':'ᐤ', //=
				
				'k_81':'ᐃ', //q
				'k_87':'ᐱ', //w
				'k_69':'ᑎ', //e
				'k_82':'ᑭ', //r
				'k_84':'ᒋ', //t
				'k_89':'ᒥ', //y
				'k_85':'ᓂ', //u
				'k_73':'ᓯ', //i
				'k_79':'ᔨ', //o
				'k_219':'᙮', //[
				'k_221':'ᐧ', //]
				
				
				'k_65':'ᐅ', //a
				'k_83':'ᐳ', //s
				'k_68':'ᑐ', //d
				'k_70':'ᑯ', //f
				'k_71':'ᒍ', //g
				'k_72':'ᒧ', //h
				'k_74':'ᓄ', //j
				'k_75':'ᓱ', //k
				'k_76':'ᔪ', //l
				'k_90':'ᐊ', //z
				'k_88':'ᐸ', //x
				'k_67':'ᑕ', //c
				'k_86':'ᑲ', //v
				'k_66':'ᒐ', //b
				'k_78':'ᒪ', //n
				'k_77':'ᓇ', //m
				'k_188':'ᓴ', //,
				'k_190':'ᔭ', //.
				
				
			};
			
			var char=charmap['k_'+charcode];
			if (d.upperkey) char=charmap['ks_'+charcode];
			if (!char) {
				console.log(charcode);
				if (d.oldkeydown) d.oldkeydown(e);
				return;
			}
			
					
		    if (document.selection) {
		        sel = document.selection.createRange();
		        sel.text = char;
		    } else {
			    if (d.selectionStart || d.selectionStart == '0') {
		        var startPos = d.selectionStart;
		        var endPos = d.selectionEnd;
		        d.value = d.value.substring(0, startPos)
		            + char
		            + d.value.substring(endPos, d.value.length);
			    } else {
			        d.value += char;
			    }
	    	}		

			if (d.oldkeydown) d.oldkeydown(e);
			
			return false;
		}
		
		d.onkeyup=function(e){
			var charcode;
			if (e) charcode=e.which; else charcode=event.charCode;
			if (charcode==16) d.upperkey=null;
			if (d.oldkeyup) d.oldkeyup(e);
		}
			
	} else {
		d.cree=null;
		d.style.border='solid 1px #cccccc';
		d.onkeydown=d.oldkeydown;
		d.onkeyup=d.oldkeyup;
	}

	
		
	d.focus();
	
		
}