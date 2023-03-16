package org.example.gradeCalculator;

import java.util.List;

public class GradeCalculator {
    private final Courses courses;
    public GradeCalculator(List<Course> courses) {
        this.courses = new Courses(courses);
    }

    public GradeCalculator(Courses courses){
        this.courses = courses;
    }
    // 이수한 과목을 전달하여 평균학점 계산 요청 ---> 학점 계산기 ---> (학점수*교과목 평점)의 합계 ---> 과목(코스) 일급컬렉션
    //                                                      ---> 수강신청 총학점 수     ---> 과목(코스) 일급컬렉션
    public double calculateGrade() {
        // (학점수+교과목 평점)의 합계
        // 일급콜렉션에게 위임할 수 있다. --> Courses 밑의 multiplyCreditAndCourseGrade()메서드로 들어간다.
        double totalMultipliedCreditAndCourseGrade =  courses.multiplyCreditAndCourseGrade();
        // 수강신청 총학점 수
        // 일급콜렉션에게 위임할 수 있다. --> Courses 밑의 calculateTotalCompletedCredit()메서드로 들어간다.
        int totalCompletedCredit = courses.calculateTotalCompletedCredit();

        return totalMultipliedCreditAndCourseGrade/totalCompletedCredit;
    }
}
