<?php
namespace Models\BookAggregate;
enum Status: string{
    case Available = "available";
    case OutOfStock = "out_of_stock";
    case Discontinued = "discontinued";
}