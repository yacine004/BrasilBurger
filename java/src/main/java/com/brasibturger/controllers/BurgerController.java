package com.brasibturger.controllers;

import com.brasibturger.dtos.BurgerDTO;
import com.brasibturger.services.BurgerService;
import lombok.RequiredArgsConstructor;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;
import jakarta.validation.Valid;
import java.util.List;

@RestController
@RequestMapping("/api/burgers")
@RequiredArgsConstructor
public class BurgerController {
    
    private final BurgerService burgerService;
    
    @GetMapping
    public ResponseEntity<List<BurgerDTO>> getAllBurgers() {
        return ResponseEntity.ok(burgerService.getAllBurgers());
    }
    
    @GetMapping("/actifs")
    public ResponseEntity<List<BurgerDTO>> getActiveBurgers() {
        return ResponseEntity.ok(burgerService.getActiveBurgers());
    }
    
    @GetMapping("/{id}")
    public ResponseEntity<BurgerDTO> getBurgerById(@PathVariable Long id) {
        return ResponseEntity.ok(burgerService.getBurgerById(id));
    }
    
    @PostMapping
    public ResponseEntity<BurgerDTO> createBurger(@Valid @RequestBody BurgerDTO burgerDTO) {
        return ResponseEntity.status(HttpStatus.CREATED)
                .body(burgerService.createBurger(burgerDTO));
    }
    
    @PutMapping("/{id}")
    public ResponseEntity<BurgerDTO> updateBurger(
            @PathVariable Long id,
            @Valid @RequestBody BurgerDTO burgerDTO) {
        return ResponseEntity.ok(burgerService.updateBurger(id, burgerDTO));
    }
    
    @DeleteMapping("/{id}")
    public ResponseEntity<Void> deleteBurger(@PathVariable Long id) {
        burgerService.deleteBurger(id);
        return ResponseEntity.noContent().build();
    }
}
