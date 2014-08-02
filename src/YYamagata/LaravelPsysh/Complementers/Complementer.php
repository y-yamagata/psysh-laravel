<?php namespace YYamagata\LaravelPsysh\Complementers;

use YYamagata\LaravelPsysh\Contributors\ContributorInterface;

class Complementer implements ComplementerInterface {

    protected $list = array();
    protected $mrulist = array();
    protected $beforeInput = '';

    public function add($contributor)
    {
        if (is_array($contributor)) {
            $this->arrange($contributor);
        } else if ($contributor instanceof ContributorInterface) {
            $this->arrange($contributor->getList());
        } else {
            throw new \InvalidArgumentException('invalid type.');
        }
    }

    protected function arrange(array $array1)
    {
        sort($array1, SORT_STRING);

        $array2 = $this->list;
        $this->list = array();
        $t = '';
        while (( ! empty($array1)) && ( ! empty($array2))) {
            $a = (string) current($array1);
            $b = (string) current($array2);
            $v = '';
            if ($a < $b) {
                $v = (string) array_shift($array1);
            } else {
                $v = (string) array_shift($array2);
            }
            if ($v === $t) {
                continue;
            }
            $this->list[] = $t = $v;
        }
        if ( ! empty($array1)) {
            foreach ($array1 as $v) {
                if ($v === $t) continue;
                $this->list[] = $t = (string) $v;
            }
        }
        if ( ! empty($array2)) {
            foreach ($array2 as $v) {
                if ($v === $t) continue;
                $this->list[] = $t = (string) $v;
            }
        }
    }

    public function register()
    {
        if ( ! function_exists('readline_completion_function')) {
            throw new \RuntimeException('require readline.');
        }
        readline_completion_function(array($this, 'callback'));
    }

    public function callback($origin, $index)
    {
        if ($origin === '') {
            return false;
        }
        $words = preg_split('/(?:::)|(?:->)|(?:\s+)/', $origin);
        $input = array_pop($words); // count($words) - 1
        if ($input === '') {
            return false;
        }

        $this->mrulist = $this->search($input);
        $this->beforeInput = $input;
        if (empty($this->mrulist)) {
            return false;
        }

        if (count($words) > 0) {
            $head = substr($origin, 0, -strlen($input));
            return array_map(function($i) use ($head) {
                return sprintf('%s%s', $head, $i);
            }, $this->mrulist);
        }
        return $this->mrulist;
    }

    protected function search($input)
    {
        $res = array();
        if (strlen($input) >= strlen($this->beforeInput)) {
            $res = $this->binarySearch($this->mrulist, $input);
        }
        if (empty($res)) {
            $res = $this->binarySearch($this->list, $input);
        }
        return $res;
    }

    protected function binarySearch($array, $input)
    {
        $len = strlen($input);

        // left search.
        $left = 0;
        $l = 0;
        $r = count($array) - 1;
        $m = 0;
        while ($r - $l > 3) {
            $m = ($r + $l) >> 1;
            if ($input > substr($array[$m], 0, $len)) {
                $l = $m + 1;
            } else {
                $r = $m - 1;
            }
        }
        for ($i = $l; $i <= $r; $i++) {
            if (strpos($array[$i], $input) === 0) break;
        }
        $left = min($r, $i);

        // right search
        $right = 0;
        $l = 0;
        $r = count($array) - 1;
        $m = 0;
        while ($r - $l > 3) {
            $m = ($r + $l) >> 1;
            if ($input >= substr($array[$m], 0, $len)) {
                $l = $m + 1;
            } else {
                $r = $m - 1;
            }
        }
        for ($i = $r; $i >= $l; $i--) {
            if (strpos($array[$i], $input) === 0) break;
        }
        $right = max($l, $i);

        if ($left === $right && strpos($array[$left], $input) !== 0) {
            return array();
        }
        return array_slice($array, $left, $right - $left + 1);
    }

}
