package org.example.mvc;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.lang.reflect.Constructor;
import java.lang.reflect.Method;

public class AnnotationHandler {
    private final Class<?> clazz;
    private final Method targetMethod;

    public AnnotationHandler(Class<?> clazz, Method targetMethod) { // 어노테이션이 붙은 클래스와 메서드를 가져온다.
        this.clazz = clazz;
        this.targetMethod = targetMethod;
    }

    public String handle(HttpServletRequest request, HttpServletResponse response) throws Exception {
        Constructor<?> defaultConstructor = clazz.getDeclaredConstructor(); // 생성자 호출 => constructor를 가져온다.
        Object targetObject = defaultConstructor.newInstance(); // constructor를 가지고 객체를 만들 수 있다.

        return (String) targetMethod.invoke(targetObject, request, response); // 메서드 호출
    }
}
