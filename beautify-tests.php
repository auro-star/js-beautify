<pre>
<?php

require_once('beautify.php');

$tests_done = 0;
$tests_passed = 0;
$tests_failed = 0;

function bt($input, $expected = Null)
{
    global $tests_failed, $tests_passed;
    if ($expected === Null) {
        $expected = $input;
    }

    $result = js_beautify($input, 4);

    if ($result != $expected) {
        printf('
---- input --------
%s
---- expected -----
%s
---- received -----
%s
-------------------',
            htmlspecialchars($input),
            htmlspecialchars($expected),
            htmlspecialchars($result));
        $tests_failed += 1;
    } else {
        $tests_passed += 1;
    }

}

function results()
{
    global $tests_failed, $tests_passed;
    if (!$tests_failed) {
        printf('All %d tests passed.', $tests_passed);
    } else {
        printf("\n%d tests failed.", $tests_failed);
    }
}
bt('');
bt('a        =          1', 'a = 1');
bt('a=1', 'a = 1');
bt("a\n=\n2", 'a = 2');
bt("a();\n\nb();", "a();\n\nb();");
bt('var a = 1 var b = 2', "var a = 1\nvar b = 2");
bt('a = " 12345 "');
bt("a = ' 12345 '");
bt('if (a == 1) b = 2', "if (a == 1)\n    b = 2");
bt('if(1){2}else{3}', "if (1) {\n    2\n} else {\n    3\n}");
bt('if(1||2)', 'if (1 || 2)');
bt('(a==1)||(b==2)', '(a == 1) || (b == 2)');
bt('var a = 1 if (2) 3', "var a = 1\nif (2)\n    3");
bt('a = a + 1');
bt('a = a == 1');
bt('/12345[^678]*9+/.match(a)');
bt('a /= 5');
bt('a = 0.5 * 3');
bt('a *= 10.55');
bt('a < .5');
bt('a <= .5');
bt('a<.5', 'a < .5');
bt('a<=.5', 'a <= .5');
bt('a = 0xff;');
bt('a=0xff+4', 'a = 0xff + 4');
bt('a = [1, 2, 3, 4]');
bt('F*(g/=f)*g+b', 'F * (g /= f) * g + b');
bt('a.b({c:d})', "a.b({\n    c: d\n})");
bt('a=!b', 'a = !b');
bt('a?b:c', 'a ? b: c'); // 'a ? b : c' would need too make parser more complex to differentiate between ternary op and object assignment
bt('a?(b):c', 'a ? (b) : c'); // this works, though
bt('function void(void) {}');
bt('if(!a)', 'if (!a)');
bt('a=~a', 'a = ~a');
bt('a;/*comment*/b;', "a;\n/*comment*/\nb;");
bt('if(a)break', "if (a)\n    break");
bt('if(a){break}', "if (a) {\n    break\n}");
bt('if((a))', 'if ((a))');
bt('for(var i=0;;)', 'for (var i = 0;;)');
bt('a++;', 'a++;');
bt('for(;;i++)', 'for (;; i++)');
bt('return(1)', 'return (1)');
bt('try{a();}catch(b){c();}finally{d();}', "try {\n    a();\n} catch(b) {\n    c();\n} finally {\n    d();\n}");
bt('(xx)()'); // magic function call
bt('a[1]()'); // another magic function call

// known problems:
# bt('if(a)if(b)break', "if (a)\n    if (b)\n        break"); // won't fix, at least now

results();

?>
</pre>
