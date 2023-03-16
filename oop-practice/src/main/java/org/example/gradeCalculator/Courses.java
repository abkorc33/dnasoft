package org.example.gradeCalculator;

import java.util.List;

public class Courses {
    // 일급 콜렉션: 리스트 형태로 된 course정보만 인스턴스 변수로 가진 클래스
    // 이 정보를 가지고 처리할 수 있는 책임들이 해당 일급컬렉션 밑으로 모두 이동하게 된다.
    // 해당 코스에 대한 총 학점 수를 수정할 때 해당 메서드만 수정하면 된다.
    private final List<Course> courses;

    public Courses(List<Course> courses) {
        this.courses = courses;
    }
    // GradeCalculator.java에 있던 정보가 모두 Courses 밑의 메서드로 들어간다.
    public double multiplyCreditAndCourseGrade() {
        // 과목들을 전체 돌면서 이수한 과목들의 학점수*성적정보를 모두 sum()하는 로직
        return courses.stream()
                .mapToDouble(Course::multiplyCreditAndCourseGrade)
                .sum();
//        해당 코드를 더 간단하게 표현
//        double multipliedCreditAndCourseGrade = 0;
//        for (Course course : courses) {
//            multipliedCreditAndCourseGrade += course.multipliyCreditAndCourseGrade();
//        }
//        return multipliedCreditAndCourseGrade;
    }

    public int calculateTotalCompletedCredit() {
        // 이수한 과목을 돌면서 해당하는 학점수들을 총 sum() 한 메서드
        return courses.stream()
                .mapToInt(Course::getCredit)
                .sum();
    }
}
