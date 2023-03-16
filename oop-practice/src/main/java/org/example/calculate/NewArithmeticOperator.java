package org.example.calculate;

public interface NewArithmeticOperator {
    //다음 operator 를 지원하는지 확인
    public boolean supports(String operator);
    int calculate(PositiveNumber operand1, PositiveNumber operand2);
}
