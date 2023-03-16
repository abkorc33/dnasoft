package org.example;

public class WrongPasswordGenerator implements PasswordGenerator{
    @Override
    public String generatePassword() {
        return "12"; // 2글자 패스워드
    }
}
