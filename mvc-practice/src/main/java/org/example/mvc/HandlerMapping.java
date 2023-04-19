package org.example.mvc;

public interface HandlerMapping {
    // 컨트롤러 인터페이스가 아니라 어노테이션으로 리퀘스트를 받기 위해서
    Object findHandler(HandlerKey handlerKey);
}