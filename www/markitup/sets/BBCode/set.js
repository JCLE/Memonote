// ----------------------------------------------------------------------------
// markItUp!
// ----------------------------------------------------------------------------
// Copyright (C) 2011 Jay Salvat
// http://markitup.jaysalvat.com/
// ----------------------------------------------------------------------------
// Html tags
// http://en.wikipedia.org/wiki/html
// ----------------------------------------------------------------------------
// Basic set. Feel free to add more tags
// ----------------------------------------------------------------------------
var mySettings = {
	onTab:    		{keepDefault:false, replaceWith:'    '},
	markupSet:  [ 	
		{name:'Gras', key:'B', openWith:'[b]', closeWith:'[/b]' },
		{name:'Italique', key:'I', openWith:'[i]', closeWith:'[/i]'  },
		{name:'Souligner', key:'S', openWith:'[u]', closeWith:'[/u]' },
		{name:'Barrer', openWith:'[s]', closeWith:'[/s]' },
                {name:'Texte Important', openWith:'[red]', closeWith:'[/red]' ,className:'color_red'},
                {separator:'---------------' },
//                {name:'Titre H1', openWith:'[h1]', closeWith:'[/h1]', className:'h1' },
                {name:'Titre H2', openWith:'[h2]', closeWith:'[/h2]', className:'h2' },
                {name:'Titre H3', openWith:'[h3]', closeWith:'[/h3]', className:'h3' },
                {name:'Titre H4', openWith:'[h4]', closeWith:'[/h4]', className:'h4' },
                {name:'Titre H5', openWith:'[h5]', closeWith:'[/h5]', className:'h5' },
                {name:'Titre H6', openWith:'[h6]', closeWith:'[/h6]', className:'h6' },
                {separator:'---------------' },
		{name:'Aligner à gauche', openWith:'[left]', closeWith:'[/left]', className:'left' },
		{name:'Aligner au centre', openWith:'[center]', closeWith:'[/center]', className:'center' },
		{name:'Aligner à droite', openWith:'[right]', closeWith:'[/right]', className:'right' },
		{name:'Alignement justifié', openWith:'[justify]', closeWith:'[/justify]', className:'justify' },
		{separator:'---------------' },
//		{name:'Bulleted List', openWith:'    <li>', closeWith:'</li>', multiline:true, openBlockWith:'<ul>\n', closeBlockWith:'\n</ul>'},
//		{name:'Numeric List', openWith:'    <li>', closeWith:'</li>', multiline:true, openBlockWith:'<ol>\n', closeBlockWith:'\n</ol>'},
//		{separator:'---------------' },
		{name:'Image', key:'P', replaceWith:'[img][![URL de l\'image:!:http://]!][/img]' ,className:'image'},
		{name:'Image flottant à gauche', replaceWith:'[img-left][![URL de l\'image:!:http://]!][/img-left]' ,className:'image-left'},
		{name:'Image flottant à droite', replaceWith:'[img-right][![URL de l\'image:!:http://]!][/img-right]' ,className:'image-right'},
                {name:'Bloc notes', openWith:'[bloc]', closeWith:'[/bloc]', className:'bloc-notes' },
		{name:'Lien', key:'L', openWith:'[url=[![Lien:!:http://]!](!( title="[![Title]!]")!)]', closeWith:'[/url]', placeHolder:'Le texte du lien...' ,className:'lien'},
                {name:'Touche du clavier', openWith:'[kbd]', closeWith:'[/kbd]' ,className:'kbd'},
                 // Added by CF Mitrah
//                {name:'Upload', key:'M',beforeInsert:function(markItUp){InlineUpload.display(markItUp,true)}},
//                {name:'Browse', key:'F',beforeInsert:function(markItUp){InlineUpload.display(markItUp,false)}},
                // Added by CF Mitrah
		{separator:'---------------' },
                {name:'Php', openWith:'[php]', closeWith:'[/php]' ,className:'php'},
                {name:'C#', openWith:'[csharp]', closeWith:'[/csharp]' ,className:'csharp'},
                {name:'Xml', openWith:'[xml]', closeWith:'[/xml]' ,className:'xml'},
                {name:'Javascript', openWith:'[js]', closeWith:'[/js]' ,className:'js'},
                {name:'Css', openWith:'[css]', closeWith:'[/css]' ,className:'css'},
                {name:'Sql', openWith:'[sql]', closeWith:'[/sql]' ,className:'sql'},
                {name:'Code', openWith:'[code]', closeWith:'[/code]' ,className:'pre'},
                {separator:'---------------' },
		{name:'Nettoyer', className:'clean', replaceWith:function(markitup) { return markitup.selection.replace(/\[(.*?)\]/g, "") } },	
//		{name:'Preview', className:'preview',  call:'preview'}
	]
}
