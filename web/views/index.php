<!DOCTYPE html>
<html>
    <head>
        <title>Hello</title>
    </head>
    <body>
        <h1>{{_helloworld_}}</h1>
        <foreach var="array" key="truc" as="value">
            {{_truc_}} : {{_value_}}<br/>
        </foreach>
        <if cond="!is_array( _array_ )">
            {{_helloworld_}}
        <else>
            a
        </if>
        {{_array['e']_}}
    </body>
</html>