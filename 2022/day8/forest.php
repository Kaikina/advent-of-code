<?php

/**
 * Returns if a tree is located at the side of the forest.
 *
 * @param int $row The row number where is located the tree
 * @param int $column The column number where is located the three
 * @param int $maxRow The maximum row number of the forest
 * @param int $maxColumn The maximum column number of the forest
 *
 * @return bool TRUE if the tree is located at one side of the forest
 */
function isSide(int $row, int $column, int $maxRow, int $maxColumn): bool {
    if ($row === 0 || $column === 0 || $row === $maxRow || $column === $maxColumn) {
        return true;
    }
    return false;
}

/**
 * Checks if a tree located inside the forest is visible from outside the forest.
 *
 * @param int $row The row number where is located the tree
 * @param int $column The column number where is located the three
 * @param array $forest The forest
 * @param int $maxRow The maximum row number of the forest
 * @param int $maxColumn The maximum column number of the forest
 *
 * @return bool
 */
function isHidden(int $row, int $column, array $forest, int $maxRow, int $maxColumn): bool {
    $hiddenFromNorth = false;
    $hiddenFromEast = false;
    $hiddenFromSouth = false;
    $hiddenFromWest = false;
    $treeHeight = $forest[$row][$column];
    // Check north
    $rowPointer = 0;
    while ($rowPointer < $row) {
        if ($forest[$rowPointer++][$column] >= $treeHeight) {
            $hiddenFromNorth = true;
        }
    }
    // Check east
    $columnPointer = $maxColumn;
    while ($columnPointer > $column) {
        if ($forest[$row][$columnPointer--] >= $treeHeight) {
            $hiddenFromEast = true;
        }
    }
    // Check south
    $rowPointer = $maxRow;
    while ($rowPointer > $row) {
        if ($forest[$rowPointer--][$column] >= $treeHeight) {
            $hiddenFromSouth = true;
        }
    }
    // Check west
    $columnPointer = 0;
    while ($columnPointer < $column) {
        if ($forest[$row][$columnPointer++] >= $treeHeight) {
            $hiddenFromWest = true;
        }
    }
    return ($hiddenFromNorth && $hiddenFromEast && $hiddenFromSouth && $hiddenFromWest);
}

/**
 * Returns if a tree is visible from outside the forest.
 *
 * @param int $row The row number where is located the tree
 * @param int $column The column number where is located the three
 * @param int $maxRow The maximum row number of the forest
 * @param int $maxColumn The maximum column number of the forest
 * @param array $forest The forest
 *
 * @return bool TRUE if the tree is visible
 */
function isVisible(int $row, int $column, int $maxRow, int $maxColumn, array $forest): bool {
    // If a tree is located at a side of the forest, it is automatically visible
    if (isSide($row, $column, $maxRow, $maxColumn)) {
        return true;
    }
    // Will return TRUE if tree is not hidden
    return !isHidden($row, $column, $forest, $maxRow, $maxColumn);
}

/**
 * Returns the scenic score of a tree.
 *
 * @param int $row The row number where is located the tree
 * @param int $column The column number where is located the three
 * @param int $maxRow The maximum row number of the forest
 * @param int $maxColumn The maximum column number of the forest
 * @param array $forest The forest
 *
 * @return int The scenic score
 */
function getScenicScore(int $row, int $column, int $maxRow, int $maxColumn, array $forest):int {
    $northScore = 0;
    $eastScore = 0;
    $southScore = 0;
    $westScore = 0;
    $treeHeight = $forest[$row][$column];

    // Get North Score
    $rowPointer = $row;
    while ($rowPointer-- > 0) {
        $treePointerHeight = $forest[$rowPointer][$column];
        $northScore++;
        if ($treePointerHeight >= $treeHeight) {
            break;
        }
    }

    // Get East Score
    $columnPointer = $column;
    while ($columnPointer++ < $maxColumn) {
        $treePointerHeight = $forest[$row][$columnPointer];
        $eastScore++;
        if ($treePointerHeight >= $treeHeight) {
            break;
        }
    }

    // Get South Score
    $rowPointer = $row;
    while ($rowPointer++ < $maxRow) {
        $treePointerHeight = $forest[$rowPointer][$column];
        $southScore++;
        if ($treePointerHeight >= $treeHeight) {
            break;
        }
    }

    // Get West Score
    $columnPointer = $column;
    while ($columnPointer-- > 0) {
        $treePointerHeight = $forest[$row][$columnPointer];
        $westScore++;
        if ($treePointerHeight >= $treeHeight) {
            break;
        }
    }

    return $northScore * $eastScore * $southScore * $westScore;
}

/*
  __  __              _____   _   _     _____    _____     ____     _____   _____               __  __ 
 |  \/  |     /\     |_   _| | \ | |   |  __ \  |  __ \   / __ \   / ____| |  __ \      /\     |  \/  |
 | \  / |    /  \      | |   |  \| |   | |__) | | |__) | | |  | | | |  __  | |__) |    /  \    | \  / |
 | |\/| |   / /\ \     | |   | . ` |   |  ___/  |  _  /  | |  | | | | |_ | |  _  /    / /\ \   | |\/| |
 | |  | |  / ____ \   _| |_  | |\  |   | |      | | \ \  | |__| | | |__| | | | \ \   / ____ \  | |  | |
 |_|  |_| /_/    \_\ |_____| |_| \_|   |_|      |_|  \_\  \____/   \_____| |_|  \_\ /_/    \_\ |_|  |_|
 */

$forest = [];
$row = 0;
$forest[$row] = [];
$visibleTrees = 0;

// Get the local file containing the forest map
$fileContent = str_split(file_get_contents('forest.txt'));

// Parse the file to create an array map of the forest
for ($i = 0; $i < count($fileContent); $i++) {
    /* End of lines are composed of a whitespace + EOL char, when we reach a non-numeric char, we create a new
       row and skip the EOL char */
    if (!is_numeric($fileContent[$i])) {
        $i++;
        $forest[++$row] = [];
    } else {
        $forest[$row][] = $fileContent[$i];
    }
}

$maxRow = count($forest) - 1;
$maxColumn = count($forest[0]) - 1;
$maxScenicScore = 0;

// Iterates over the forest array map
for ($row = 0; $row <= $maxRow; $row++) {
    for ($column = 0; $column <= $maxColumn; $column++) {
        if (isVisible($row, $column, $maxRow, $maxColumn, $forest)) {
            $visibleTrees++;
        }
        $scenicScore = getScenicScore($row, $column, $maxRow, $maxColumn, $forest);
        if ($scenicScore > $maxScenicScore) {
            $maxScenicScore = $scenicScore;
        }
    }
}

echo $visibleTrees . " visible trees." . "<br>";
echo $maxScenicScore . " is the best scenic score available." . "<br>";
