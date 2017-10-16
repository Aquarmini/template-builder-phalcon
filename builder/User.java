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
public class User extends BaseEntity {

    private Integer id;

    private String username;

    private String password;

    private String name;

    private String email;

    private Integer role_id;

    private LocalDateTime created_at;

    private LocalDateTime updated_at;


}