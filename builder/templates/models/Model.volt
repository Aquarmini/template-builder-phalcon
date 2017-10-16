package com.phalcon.template.builder;

import com.phalcon.common.BaseEntity;
import lombok.Data;

import java.time.LocalDateTime;

/**
*
* @Author limx
* @Date 2017-09-28
* @Time 20：19
*/
@Data
public class {{ modelClass }} extends BaseEntity {

{% for field in fields %}
    private {{ field['type'] }} {{ field['name'] }};

{% endfor %}

}