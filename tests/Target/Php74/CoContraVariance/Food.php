<?php


namespace Phabel\Target\Php74\CoContraVariance;

class Food {}

class AnimalFood extends Food {}


abstract class Animal
{
    protected $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }


    public function eat(AnimalFood $food): string
    {
        return $this->name . " eats " . get_class($food);
    }
}

class Dog extends Animal
{

    public function eat(Food $food): string
    {
        return $this->name . " eats " . get_class($food);
    }
}

class Cat extends Animal
{
}

interface AnimalShelter
{
    public function adopt(string $name): Animal;
}

class CatShelter implements AnimalShelter
{
    public function adopt(string $name): Cat // instead of returning class type Animal, it can return class type Cat
    {
        return new Cat($name);
    }
}

class DogShelter implements AnimalShelter
{
    public function adopt(string $name): Dog // instead of returning class type Animal, it can return class type Dog
    {
        return new Dog($name);
    }
}