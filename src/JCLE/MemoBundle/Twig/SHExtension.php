<?php

namespace JCLE\MemoBundle\Twig;

class SHExtension extends \Twig_Extension 
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('sh', array($this, 'shcreate')),
        );
    }
    
    public function shcreate($text)
    {
        // Convertit les ouvertures et fermetures de balises 
        // afin de les affichées pour le colorateur synthaxique
        $text = str_replace("<", "&lt;", $text);
        $text = str_replace(">", "&gt;", $text);
        
        // Supprime les balises (si certaines passent à travers les mailles)
        strip_tags($text);
        
        // http://www.gethifi.com/tools/regex pour création expression reguliere
        $conv = array(
            '\\n'    =>  ''
//            '(\[php\])(.*?)\\n*(.*?)(\[\/php\])'    =>  '$1$2$3$4',
            ,'\[b\](.*?)\[\/b\]'    =>  '<strong>$1</strong>'
            ,'\[i\](.*?)\[\/i\]'    =>  '<em>$1</em>'
            ,'\[u\](.*?)\[\/u\]'    =>  '<u>$1</u>'
            ,'\[s\](.*?)\[\/s\]'    =>  '<span style="text-decoration:line-through;">$1</span>' 
//            ,'\[h1\](.*?)\[\/h1\]'    =>  '<h1>$1</h1>' 
            ,'\[h2\](.*?)\[\/h2\]'    =>  '<h2>$1</h2>' 
            ,'\[h3\](.*?)\[\/h3\]'    =>  '<h3>$1</h3>' 
            ,'\[h4\](.*?)\[\/h4\]'    =>  '<h4>$1</h4>' 
            ,'\[h5\](.*?)\[\/h5\]'    =>  '<h5>$1</h5>' 
            ,'\[h6\](.*?)\[\/h6\]'    =>  '<h6>$1</h6>' 
            ,'\[kbd\](.*?)\[\/kbd\]'    =>  '<kbd>$1</kbd>' 
            ,'\[img\](.*?)\[\/img\]'    =>  '<img src="$1" class="resize-image" />'
            ,'\[img-left\](.*?)\[\/img-left\]'    =>  '<img src="$1" class="pull-left resize-image"/>'
            ,'\[img-right\](.*?)\[\/img-right\]'    =>  '<img src="$1" class="pull-right resize-image"/>'
            ,'\[bloc\](.*?)\[\/bloc\]'    =>            '<div class="row"><div class="col-lg-12">$1</div></div>'
            ,'\[url=([^\]]*)\](.*?)\[\/url\]'    =>  '<a href="$1">$2</a>'
            ,'\[code\](.*?)\[\/code\]'    =>  '<pre>$1</pre>'
            ,'\[code=\](.*?)\[\/code\]'    =>  '<pre type="syntaxhighlighter" class="brush: plain">$1</pre>' 
            ,'\[code=(.*?)\](.*?)\[\/code\]'    =>  '<pre type="syntaxhighlighter" class="brush: $1">$2</pre>'  
            ,'\[center\](.*?)\[\/center\]'    =>  '<div class="text-center">$1</div>' 
            ,'\[left\](.*?)\[\/left\]'    =>  '<div class="text-left">$1</div>' 
            ,'\[right\](.*?)\[\/right\]'    =>  '<div class="text-right">$1</div>' 
            ,'\[justify\](.*?)\[\/justify\]'    =>  '<div class="text-justify">$1</div>' 
            ,'\[red\](.*?)\[\/red\]'    =>  '<code>$1</code>' 
        );
        
        foreach ($conv as $key => $value)
        {
            $text = preg_replace('/'.$key.'/',$value,$text);
        }
                
        return $text;
    }

    public function getName()
    {
        return 'sh_extension';
    }
}
