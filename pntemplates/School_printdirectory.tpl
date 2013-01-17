	<div id="BluebookPrint">
	<h1>Blue Book <!--[$ThisYear]--></h1>
        <!--[foreach item=d from=$data]-->
          <div>
            <b><!--[$d.FamilyName]--></b><br />
            <!--[$d.Address]--><br />
            <!--[$d.City]-->, <!--[$d.State]--> <!--[$d.Zip]--><br />
            <!--[$d.Phone]--><br />
	    <!--[foreach from=$d.students item=s name=foo]-->
		<!--[if $s.NickName]--><!--[$s.NickName]--><!--[else]--><!--[$s.FirstName]--><!--[/if]-->
		<!--[$s.ClassYear|Year2Grade]--><!--[if !$smarty.foreach.foo.last]-->, <!--[/if]-->
	    <!--[/foreach]--><br />
            <!--[$d.Email]--><br />
            <br />
           </div>
        <!--[/foreach]-->
	</div>
