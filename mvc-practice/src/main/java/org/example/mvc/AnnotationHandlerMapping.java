package org.example.mvc;

import org.example.mvc.annotation.RequestMapping;
import org.example.mvc.controller.RequestMethod;
import org.reflections.Reflections;

import java.util.Arrays;
import java.util.HashMap;
import java.util.Map;
import java.util.Set;

public class AnnotationHandlerMapping implements HandlerMapping{
    private final Object[] basePackage;

    private final Map<HandlerKey, AnnotationHandler> handlers = new HashMap<>();

    public AnnotationHandlerMapping(Object... basePackage) {
        this.basePackage = basePackage;
    } // 생성자

    public void initialize() { // 초기화
        Reflections reflections = new Reflections(basePackage); // 어노테이션을 찾기 위해 리플렉션 사용

        Set<Class<?>> clazzesWithControllerAnnotation = reflections.getTypesAnnotatedWith(org.example.mvc.annotation.Controller.class, true);

        clazzesWithControllerAnnotation.forEach(clazz -> // 클래스정보
                // 클래스에 있는 모든 메소드를 foreach돌린다 => 클래스, 메서드에 붙은 어노테이션 찾기위해
                Arrays.stream(clazz.getDeclaredMethods()).forEach(declaredMethod -> { // 메서드정보
                    // 메서드에 requestMapping이 붙은애를 찾는다.
                    RequestMapping requestMappingAnnotation = declaredMethod.getDeclaredAnnotation(RequestMapping.class);

                    Arrays.stream(getRequestMethods(requestMappingAnnotation))
                            .forEach(requestMethod -> handlers.put(
                                    new HandlerKey(requestMappingAnnotation.value(), requestMethod), new AnnotationHandler(clazz, declaredMethod)
                            ));

                })
        );
    }

    private RequestMethod[] getRequestMethods(RequestMapping requestMappingAnnotation) { // get, post여러개 받기 위함
        return requestMappingAnnotation.method();
    }

    @Override
    public Object findHandler(HandlerKey handlerKey) {
        return handlers.get(handlerKey);
    }
}
