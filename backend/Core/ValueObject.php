<?php

namespace Core;

/**
 * Lớp trừu tượng cơ sở cho Value Object theo DDD.
 * Đảm bảo tính bất biến (Immutability) và so sánh dựa trên giá trị (Value Equality).
 */
abstract class ValueObject
{
    /**
     * Phương thức trừu tượng yêu cầu các lớp con định nghĩa
     * để trả về các thành phần cấu thành giá trị (Value Components).
     *
     * @return array Danh sách các thuộc tính (giá trị) của Value Object.
     */
    abstract protected function getEqualityComponents(): array;

    /**
     * Kiểm tra tính bằng nhau dựa trên giá trị (Value Equality).
     *
     * @param self|null $other Đối tượng ValueObject khác để so sánh.
     * @return bool
     */
    public function equals(?self $other): bool
    {
        // 1. Kiểm tra tham chiếu null
        if ($other === null) {
            return false;
        }

        // 2. Kiểm tra cùng lớp
        if (get_class($this) !== get_class($other)) {
            return false;
        }

        // 3. So sánh các thành phần giá trị
        $components = $this->getEqualityComponents();
        $otherComponents = $other->getEqualityComponents();
        
        // Sử dụng một vòng lặp để so sánh từng thành phần
        foreach ($components as $key => $value) {
            if (!array_key_exists($key, $otherComponents)) {
                return false;
            }
            
            // So sánh các thành phần:
            // Nếu thành phần là Value Object, gọi phương thức equals() của nó.
            // Nếu là kiểu nguyên thủy, sử dụng toán tử ===
            if ($value instanceof self) {
                if (!$value->equals($otherComponents[$key])) {
                    return false;
                }
            } elseif ($value !== $otherComponents[$key]) {
                return false;
            }
        }

        // Đảm bảo không có thành phần nào bị thừa ở phía $other
        if (count($components) !== count($otherComponents)) {
             return false;
        }

        return true;
    }

    /**
     * Phương thức Magic __toString, giúp dễ dàng debug hoặc sử dụng
     * trong các hàm yêu cầu chuỗi (optional).
     * @return string
     */
    public function __toString(): string
    {
        $components = $this->getEqualityComponents();
        $str = [];
        foreach ($components as $key => $value) {
            $val = $value instanceof self ? $value->__toString() : (string) $value;
            $str[] = "{$key}:{$val}";
        }
        return get_class($this) . '[' . implode(', ', $str) . ']';
    }

    // Ghi chú: PHP không hỗ trợ Override GetHashCode() hay operator ==/!= 
    // một cách trực tiếp như C#.
    // Đối với việc so sánh bằng toán tử ===, PHP sẽ so sánh thuộc tính,
    // điều này hoạt động gần giống với Value Equality nếu bạn chỉ sử dụng
    // các thuộc tính được liệt kê trong getEqualityComponents().
}