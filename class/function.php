<?php
class functions{
  // 学年の表示設定
  public function gradeJudge($grade){
    switch($grade){
      case 0: $grade_name = "選択してください"; break;
      case 1: $grade_name = "小学一年生"; break;
      case 2: $grade_name = "小学二年生"; break;
      case 3: $grade_name = "小学三年生"; break;
      case 4: $grade_name = "小学四年生"; break;
      case 5: $grade_name = "小学五年生"; break;
      case 6: $grade_name = "小学六年生"; break;
      case 7: $grade_name = "中学一年生"; break;
      case 8: $grade_name = "中学二年生"; break;
      case 9: $grade_name = "中学三年生"; break;
      case 10: $grade_name = "高校一年生"; break;
      case 11: $grade_name = "高校二年生"; break;
      case 12: $grade_name = "高校三年生"; break;
    }
    return $grade_name;
  }

  // 解決未解決の表示設定
public function solveCheck($solve_check){
  switch($solve_check){
    case 0: return "未解決"; break;
    case 1: return "解決済み"; break;
    default: return "不明";
  }
}

}