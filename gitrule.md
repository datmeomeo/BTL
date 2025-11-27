git init --> tạo thư mục .git
git add .
git commit -m "chú thích bạn đã thay đổi gì"
git push origin main  (đang đẩy code lên nhánh main, điều kiện đồng bộ về lịch sử commit)
git branch -a (xem danh sách các nhanh hiện có trong local tức trong thư mục .git ở máy)
git fetch (lấy thay đổi từ remote về local)
git pull origin main (lấy thay đổi từ remote main về nhánh hiện tại)
git checkout -b datt (copy nhánh hiện tại sang một nhánh mới có tên là datt)
git checkout "tenNhanh" (chuyển sang nhánh hiện có bất kể là local hay remote)

Quy trình code một chức năng mới 
B1: git pull origin main (Điều kiện là phải đang ở nhánh main)
B2: git checkout -b newFunction
B3: Code và push nhánh newFunction này lên remote
B4: Tạo pull request trên remote từ nhánh newFunction vào nhánh main
Những thành viên còn lại trước khi muốn tạo pull request vào main cần làm:
1. Git pull origin main
2. Xử lý xung đột (nếu có)
3. git push origin YourFunctionBranch
4. Tạo pull request vào nhánh main
Các thành viên không được code trên nhánh của nhau.