package org.example;

import org.example.mvc.annotation.Controller;
import org.example.mvc.annotation.Service;
import org.example.mvc.model.User;
import org.junit.jupiter.api.Test;
import org.reflections.Reflections;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import java.lang.annotation.Annotation;
import java.util.Arrays;
import java.util.HashSet;
import java.util.List;
import java.util.Set;
import java.util.stream.Collectors;

public class ReflectionTest {

    private static final Logger logger = LoggerFactory.getLogger(ReflectionTest.class);
    @Test
    void controllerScan() {
        Set<Class<?>> beans = getTypesAnnotatedWith(List.of(Controller.class, Service.class));

        logger.debug("beans: [{}]", beans);
    }

    @Test
    void showClass() {
        Class<User> clazz = User.class;
        Logger.debug(clazz.getName());

        // 클래스에 선언된 필드들을 가져올 수 있다.
        Logger.debug("User all declared fields: [{}]", Arrays.stream(clazz.getDeclaredFields()).collect(Collectors.toList()));
        Logger.debug("User all declared fields: [{}]", Arrays.stream(clazz.getDeclaredFields()).collect(Collectors.toList()));
    }

    private Set<Class<?>>  getTypesAnnotatedWith(List<Class<? extends Annotation>> annotations){
        Reflections reflections = new Reflections("org.example");
        // 해당 패키지 내에 있는 클래스에서 컨트롤러라는 어노테이션이 붙어있는 대상들을 찾는다. ==> 해쉬셋에 담는 코드
        Set<Class<?>> beans = new HashSet<>();
        // 어노테이션을 하나씩 foreach돌면서 해쉬셋에 add를 한 것.
        annotations.forEach(annotation -> beans.addAll(reflections.getTypesAnnotatedWith(annotation)));
        beans.addAll(reflections.getTypesAnnotatedWith(Controller.class));
        beans.addAll(reflections.getTypesAnnotatedWith(Service.class));
        return beans;
    }
}
