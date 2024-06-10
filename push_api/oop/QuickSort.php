<?php

class QuickSort {

    public static function sort(array &$arr) {
        try {
            $length = count($arr);
            self::quickSort($arr, 0, $length - 1);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        } finally {
            $date = date("Y-m-d H:i:s");
            echo "-- sort timestamp: $date  --\n";
        }
    }

    private static function quickSort(array &$arr, $left, $right) {
        if ($left < $right) {
            $pivotIndex = self::partition($arr, $left, $right);
            self::quickSort($arr, $left, $pivotIndex - 1);
            self::quickSort($arr, $pivotIndex + 1, $right);
        }
    }

    private static function partition(array &$arr, $left, $right) {
        $pivot = $arr[$right];
        $i = $left - 1;

        for ($j = $left; $j < $right; $j++) {
            if ($arr[$j] < $pivot) {
                $i++;
                self::swap($arr, $i, $j);
            }
        }

        self::swap($arr, $i + 1, $right);
        return $i + 1;
    }

    private static function swap(array &$arr, $i, $j) {
        $temp    = $arr[$i];
        $arr[$i] = $arr[$j];
        $arr[$j] = $temp;
    }
}

// Example usage:
$arr = [3, 1, 4, 1, 5, 9, 2, 6, 5, 3, 5, 'AB', 'BC', 'AZ'];
QuickSort::sort($arr);
print_r($arr);